<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DatePeriod;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $daysOfWeek = [
        'Monday' => Carbon::MONDAY,
        'Tuesday' => Carbon::TUESDAY,
        'Wednesday' => Carbon::WEDNESDAY,
        'Thursday' => Carbon::THURSDAY,
        'Friday' => Carbon::FRIDAY,
        'Saturday' => Carbon::SATURDAY,
        'Sunday' => Carbon::SUNDAY
    ];

    /**
     * Compute a range between two dates, and generate
     * a plain array of Carbon objects of each day in it.
     *
     * @param  string  $yearMonth
     * @param  bool  $inclusive
     * @return array|null
     */
    function dateRange(string $yearMonth, $inclusive = true)
    {
        $from = Carbon::parse($yearMonth)->format("Y-m-01");
        $from = Carbon::parse($from);
        $to = Carbon::parse($yearMonth)->format("Y-m-") . Carbon::parse($yearMonth)->daysInMonth;
        $to = Carbon::parse($to);

        if ($from->gt($to)) {
            return null;
        }

        // Clone the date objects to avoid issues, then reset their time
        $from = $from->copy()->startOfDay();
        $to = $to->copy()->startOfDay();

        // Include the end date in the range
        if ($inclusive) {
            // $from->addDay();
            $to->addDay();
            // $to->addDay();
        }

        $step = CarbonInterval::day();
        $period = new DatePeriod($from, $step, $to);

        // Convert the DatePeriod into a plain array of Carbon objects
        $range = [];

        foreach ($period as $day) {
            $range[] = Carbon::parse(new Carbon($day))->format("Y-m-d");
        }

        return !empty($range) ? $range : null;
    }


    /**
     * Generate a range of dates between two Carbon instances.
     *
     * @param \Carbon\Carbon $from         The starting date.
     * @param \Carbon\Carbon $to           The ending date.
     * @param string    $formatDate   The format to be applied to each date in the range.
     * @param bool           $inclusive    (Optional) Whether to include the starting and ending dates in the range.
     *
     * @return array|null                  An array of dates formatted according to the specified format, or null if the range is empty.
     *
     * kebutuhan tanggal mulai setelah tutup buku yaitu 26, sampai tanggal tutup buku 25
     */
    function dateRangeCustom(Carbon $month, String $formatDate, string $typeResult, bool $inclusive = true)
    {
        // Define the valid enum values
        $validValues = ['string', 'object'];

        // Validate the parameter value
        if (!in_array($typeResult, $validValues)) {
            throw new InvalidArgumentException('Invalid parameter value');
        }

        $currentMonth = $month->month;
        $currentYear = $month->year;
        $dateStart = Carbon::create($currentYear, $currentMonth, 1)->subMonth()->addDay(25);
        $dateEnd = Carbon::create($currentYear, $currentMonth, 1)->addDay(24);

        // Clone the date objects to avoid issues, then reset their time
        // $from = $from->copy()->startOfDay();
        // $to = $to->copy()->startOfDay();

        $step = CarbonInterval::day();
        $period = new DatePeriod($dateStart, $step, $dateEnd);

        // Include the end date in the range
        if ($inclusive) {
            // $dateStart->addDay();
            $dateEnd->addDay();
            // $dateEnd->addDay();
        }

        $step = CarbonInterval::day();
        $period = new DatePeriod($dateStart, $step, $dateEnd);

        // Convert the DatePeriod into a plain array of Carbon objects
        $range = [];

        foreach ($period as $day) {
            if ($typeResult == "object") {
                $range[] = (object) [
                    "date" => Carbon::parse(new Carbon($day))->format($formatDate),
                    "date_full" => Carbon::parse(new Carbon($day))->format("Y-m-d"),
                    "day" => Carbon::parse(new Carbon($day))->isoFormat("dddd"),
                ];
            } else {
                $range[] = Carbon::parse(new Carbon($day))->format($formatDate);
            }
        }

        return !empty($range) ? $range : null;
    }

    /**
     * mendapatkan tanggal - tanggal berdasarkan nama hari
     *
     * @param  string  $dayName
     * @param  string  $yearMonth
     *
     * @author haidi
     */
    function getDatesByDayName($dayName, $yearMonth)
    {
        Carbon::setLocale('en_US');
        $dates = [];

        // Parse the year and month from the input
        $yearMonth = Carbon::createFromFormat('Y-m', $yearMonth);

        // Set the first day of the week to Monday
        $yearMonth->setWeekStartsAt($this->daysOfWeek[$dayName]);

        // Loop through all dates in the month
        for ($i = 1; $i <= $yearMonth->daysInMonth; $i++) {
            $date = Carbon::createFromDate($yearMonth->year, $yearMonth->month, $i);

            // Check if the day name matches the input
            if (strcasecmp($date->format('l'), $dayName) === 0) {
                $dates[] = $date;
            }
        }

        return $dates;
    }
}
