<?php

namespace App\Exports;

use App\Exports\Sheets\RosterMainSheet;
use App\Exports\Sheets\RosterTotalSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RosterExport implements WithMultipleSheets
{

    private $main;
    private $total;
    private $date;
    private $positions;


    function __construct($main, $total, $date, $positions)
    {
        $this->main = $main;
        $this->total = $total;
        $this->date = $date;
        $this->positions = $positions;
    }

    public function sheets(): array
    {
        $sheets = [];

        $main = $this->main;
        $total = $this->total;
        $date = $this->date;
        $positions = $this->positions;

        $sheets[] = new RosterMainSheet($main, $date);
        $sheets[] = new RosterTotalSheet($total, $date, $positions);

        return $sheets;
    }
}
