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
        return Pembelian::with('customer', 'details.produk')->get()->map(function ($item) {
            $produkList = $item->details->map(function ($detail) {
                return $detail->produk->name_product . ' (x' . $detail->qty . ')';
            })->implode(', ');

            return [
                'Nama Pelanggan'       => $item->customer->name ?? 'NON-MEMBER',
                'No. HP'      => $item->customer->phone_number ?? '-',
                'Produk'               => $produkList,
                'Total Harga'          => $item->total_price ?? '-',
                'Total Bayar'          => $item->bayar ?? '-',
                'Total Diskon Poin'    => ($item->used_points > 0) ? $item->used_points : '-',
                'Total Kembalian'      => ($item->change > 0) ? $item->change : '-',
                'Tanggal Pembelian'    => $item->created_at->format('d-m-Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'No HP Pelanggan',
            'Produk',
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
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
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

                $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFCCE5FF'],
                    ],
                ]);
            },
        ];
    }
}
