<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class SalaryAdvanceExport implements FromView, WithTitle, WithStyles, ShouldAutoSize, WithDrawings
{

    protected $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Laporan Kasbon';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('logo');
        $drawing->setDescription('This is logo');
        $drawing->setPath(public_path('/assets/img/logo.png'));
        $drawing->setHeight(45);
        $drawing->setCoordinates('C1');

        return $drawing;
    }

    public function view(): View
    {
        return view('pages.salary-advance-report.partials.export', [
            'data' => $this->data,
        ]);
    }

    public function styles(Worksheet $sheet)
    {

        $alignmentStyle = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];

        $allDataRange = $sheet->calculateWorksheetDimension();
        $sheet->getStyle($allDataRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $sheet->getStyle($allDataRange)->applyFromArray($alignmentStyle);

        // BAGIAN : TANGGAL
        $sheet->getStyle('A2:I2')->applyFromArray([
            'fill' => [
                'fillType' => 'solid',
                'rotation' => 0,
                'color' => ['rgb' => '000000'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
        ]);

        $columnCount = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString('A');
        $maxRow = $sheet->getHighestRow();

        for ($row = 3; $row <= $maxRow; $row++) {
            for ($column = 1; $column <= 9; $column++) {
                $range = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnCount + $column - 1) . $row;
                $color = $row % 2 == 0 ? 'fcf9ce' : 'fbf49b';
                $sheet->getStyle($range)->applyFromArray([
                    'fill' => [
                        'fillType' => 'solid',
                        'rotation' => 0,
                        'startColor' => ['rgb' => $color],
                        'endColor' => ['rgb' => $color],
                    ],
                ]);
            }
        }
    }
}
