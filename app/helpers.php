<?php

use Carbon\Carbon;
use Carbon\CarbonInterval;

if (
    !function_exists('isActive')
) {

    /**
     * Checks if the current request path matches the given path and returns the 'active' class if true.
     *
     * @param string $path The path to check against the current request path.
     * @return string Returns 'active' if the current request path matches the given path; otherwise, an empty string.
     */

    function isActive($path)
    {
        return request()->is($path) ? 'active' : '';
    }
}

if (
    !function_exists('dateReadable')
) {

    /**
     * Format a date into a readable format using the Indonesian locale.
     *
     * @param string|\DateTimeInterface $date The date to format.
     * @return string The formatted date in the "dddd, D MMMM YYYY" format using the Indonesian locale.
     */

    function dateReadable($date)
    {
        return Carbon::parse($date)->locale('id')->isoFormat("dddd, D MMMM YYYY");
    }
}

if (
    !function_exists('dateDuration')
) {

    /**
     * Calculate the duration in days between two Carbon dates.
     *
     * @param string $date_start The start date with format Y-m-d.
     * @param string $date_end The end date with format Y-m-d.
     * @return int The duration in days.
     */
    function dateDuration($date_start, $date_end)
    {
        $getDateStart = Carbon::createFromFormat('Y-m-d', $date_start);
        $getDateEnd = Carbon::createFromFormat('Y-m-d', $date_end);

        $duration = $getDateEnd->diffInDays($getDateStart);

        return $duration;
    }
}

if (
    !function_exists('dateTimeDuration')
) {

    /**
     * Calculate the duration between two datetime values and optionally format it in a readable format.
     *
     * @param string $datetime_start The start datetime value.
     * @param string $datetime_end The end datetime value.
     * @param bool $is_readable Whether to format the duration in a readable format.
     * @return int|string The duration in minutes or a formatted duration string.
     */
    function dateTimeDuration($datetime_start, $datetime_end, $is_readable = false)
    {
        $start = Carbon::parse($datetime_start);
        $end = Carbon::parse($datetime_end);
        $totalMinutes = $end->diffInMinutes($start);
        $duration = $start->diff($end);

        if (!$is_readable) {
            return $totalMinutes;
        } else {
            $days = $duration->days;
            $hours = $duration->h;
            $minutes = $duration->i;

            $durationString = '';

            if ($days > 0) {
                $durationString .= $days . ' hari ';
            }
            if ($hours > 0) {
                $durationString .= $hours . ' jam ';
            }
            if ($minutes > 0) {
                $durationString .= $minutes . ' menit';
            }

            return $durationString;
        }
    }
}

if (
    !function_exists('limitString')
) {
    function limitString($string, $limit = 200, $suffix = '...')
    {
        if (mb_strlen($string) <= $limit) {
            return $string;
        } else {
            $trimmedString = mb_substr($string, 0, $limit);
            return $trimmedString . $suffix;
        }
    }
}
