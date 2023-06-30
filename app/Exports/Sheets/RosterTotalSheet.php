<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class RosterTotalSheet implements FromView, WithTitle, ShouldAutoSize, WithStyles, WithDrawings
{
    protected $data;
    protected $dates;
    protected $positions;

    function __construct($data, $dates, $positions)
    {
        $this->data = $data;
        $this->dates = $dates;
        $this->positions = $positions;
    }

    public function title(): string
    {
        return 'TOTAL';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('logo');
        $drawing->setDescription('This is logo');
        $drawing->setPath(public_path('/assets/img/logo.png'));
        $drawing->setHeight(45);
        $drawing->setCoordinates('B1');

        return $drawing;
    }


    public function view(): View
    {
        return view('pages.roster.exports.total', [
            'data' => $this->data,
            'dates' => $this->dates,
            'positions' => $this->positions,
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
        $sheet->getStyle('B3:AG3')->applyFromArray([
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
        $sheet->getStyle('B2:AG2')->applyFromArray([
            'fill' => [
                'fillType' => 'solid',
                'rotation' => 0,
                'color' => ['rgb' => 'CCFFFF'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000'],
            ],
        ]);

        // BAGIAN : NAMA
        $sheet->getStyle('A2')->applyFromArray([
            'fill' => [
                'fillType' => 'solid',
                'rotation' => 0,
                'color' => ['rgb' => '99CCFF'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'bold' => true,
            ],
        ]);

        // BAGIAN : DEPARTEMEN
        $sheet->getStyle('A2')->applyFromArray([
            'fill' => [
                'fillType' => 'solid',
                'rotation' => 0,
                'color' => ['rgb' => '99CCFF'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'bold' => true,
            ],
        ]);

        // Set yellow background for column A starting from row 5
        $columnCount = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString('A');
        $maxRow = $sheet->getHighestRow();

        for ($row = 4; $row <= $maxRow; $row++) {
            $range = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnCount) . $row;
            $color = $row % 2 == 0 ? 'fcf9ce' : 'fbf49b';
            $sheet->getStyle($range)->applyFromArray([
                'fill' => [
                    'fillType' => 'solid',
                    'rotation' => 0,
                    'color' => ['rgb' => $color],
                ],
            ]);
        }

        // Set yellow background for column A starting from row 5
        $columnCountB = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString('B');
        $maxRowB = $sheet->getHighestRow();

        for ($row = 4; $row <= $maxRowB; $row++) {
            $range = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnCountB) . $row;
            $color = $row % 2 == 0 ? 'fcf9ce' : 'fbf49b';
            $sheet->getStyle($range)->applyFromArray([
                'fill' => [
                    'fillType' => 'solid',
                    'rotation' => 0,
                    'color' => ['rgb' => $color],
                ],
            ]);
        }

        // BAGIAN : HARI
        // $sheet->getStyle('C4:AG4')->applyFromArray([
        //     'fill' => [
        //         'fillType' => 'solid',
        //         'rotation' => 0,
        //         'color' => ['rgb' => 'D9D9D9'],
        //     ],
        //     'borders' => [
        //         'allBorders' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //         ],
        //     ],
        //     'font' => [
        //         'bold' => true,
        //         'color' => ['rgb' => '000000'],
        //     ],
        // ]);
        // $sheet->getStyle('B12:J12')->applyFromArray($Border);
        // $sheet->getStyle('B12:J12')->getFill()->applyFromArray(['fillType' => 'solid', 'rotation' => 0, 'color' => ['rgb' => 'D9D9D9'], 'border' => 'thin']);
        // $sheet->getStyle('B12:J12')->getAlignment()->setHorizontal('center');

    }
}
