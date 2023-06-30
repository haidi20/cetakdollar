<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;

class ProjectExport implements FromView, WithTitle, ShouldAutoSize, WithStyles
{
    protected $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Data Proyek';
    }

    public function view(): View
    {
        return view('pages.project.partials.export', [
            'data' => $this->data,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }
}
