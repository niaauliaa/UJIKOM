<?php

namespace App\Exports;

use App\Models\Pembelian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;

class PenjualanExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        $data = collect();
    
        $pembelian = Pembelian::with('customer', 'details.produk')->get();
    
        foreach ($pembelian as $item) {
            foreach ($item->details as $detail) {
                $data->push([
                    'Id Pelanggan'         => $item->id,
                    'Nama Pelanggan'       => $item->customer->name ?? 'NON-MEMBER',
                    'No HP Pelanggan'      => $item->customer->phone_number ?? '-',
                    'Nama Produk'          => $detail->produk->name_product ?? '-',
                    'QTY'                  => $detail->qty,
                    'Harga Satuan'         => $detail->produk->price ?? '-',
                    'Total Harga'          => $item->total_price ?? '-',
                    'Total Bayar'          => $item->bayar ?? '-',
                    'Total Diskon Poin'    => ($item->used_points > 0) ? $item->used_points : '-',
                    'Total Kembalian'      => ($item->change > 0) ? $item->change : '-',
                    'Tanggal Pembelian'    => $item->created_at->format('d-m-Y'),
                ]);
            }
        }
    
        return $data;
    }

    public function headings(): array
    {
        return [
            'Id Pelanggan',
            'Nama Pelanggan',
            'No.Hp',
            'Nama Produk',
            'QTY',
            'Harga Satuan',
            'Total Harga',
            'Total Bayar',
            'Total Diskon Poin',
            'Total Kembalian',
            'Tanggal Pembelian',
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

                $sheet->insertNewRowBefore(1, 1);
                $sheet->setCellValue('A1', 'Daftar Penjualan');

                $lastColumn = $sheet->getHighestColumn();
                $lastRow = $sheet->getHighestRow();

                $sheet->mergeCells("A1:{$lastColumn}1");

                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFF99']],
                ]);

                $range = "A1:{$lastColumn}{$lastRow}";
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

                $sheet->getStyle("A2:{$lastColumn}2")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFCCE5FF'],
                    ],
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                ]);
            },
        ];
    }
}
