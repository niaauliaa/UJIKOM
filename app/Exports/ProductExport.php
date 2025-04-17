<?php

namespace App\Exports;

use App\Models\Product;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProductExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{  
    public function collection()
    {
        return Product::all()->map(function ($item) {
            return [
                'Nama Produk' => $item->name_product,
                'Harga'       => 'Rp.' . number_format($item->price, 0, ',', '.'),
                'Stok'        => $item->stock,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'Harga',
            'Stok',
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

                // Menambahkan judul di atas tabel
                $sheet->insertNewRowBefore(1, 1); // Menyisipkan baris baru di atas baris pertama
                $sheet->setCellValue('A1', 'Daftar Produk'); // Judul tabel
                $sheet->mergeCells('A1:' . $lastColumn . '1'); // Merge seluruh kolom untuk judul
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16], // Font bold dan ukuran besar untuk judul
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'], // Pusatkan teks
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFF99']], // Latar belakang kuning
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

                // Menambahkan gaya pada baris judul (Nama Produk, Harga, Stok)
                $sheet->getStyle('A2:' . $lastColumn . '2')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFCCE5FF']], // Warna latar belakang biru muda
                ]);
            },
        ];
    }
}
