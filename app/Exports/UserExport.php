<?php


namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;

class UserExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        return User::all()->map(function ($item) {
            return [
                'Nama'  => $item->name,
                'Email' => $item->email,
                'Role'  => $item->role,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Role',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
                $range = "A1:{$lastColumn}{$lastRow}";

               
                $sheet->insertNewRowBefore(1, 1); 
                $sheet->setCellValue('A1', 'Daftar Pengguna'); // Judul tabel
                $sheet->mergeCells('A1:' . $lastColumn . '1'); 
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16], 
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'], 
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFF99']], 
                ]);

                // Menambahkan gaya pada tabel
                $sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF999999'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                        'wrapText' => true,
                    ],
                ]);

                // Menambahkan gaya pada baris judul (Nama, Email, Role)
                $sheet->getStyle('A2:' . $lastColumn . '2')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFCCE5FF']], // Warna latar belakang biru muda
                ]);
            },
        ];
    }
}
