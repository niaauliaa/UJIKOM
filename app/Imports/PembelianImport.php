<?php
namespace App\Imports;

use App\Models\Product;
use App\Models\Pembelian;
use App\Models\DetailTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PembelianImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if ($key === 0) continue; // skip header row

            $product = Product::where('name', $row[0])->first();
            if (!$product) continue;

            $jumlah = (int)$row[1];
            $total = $product->price * $jumlah;

            $pembelian = Pembelian::create([
                'user_id' => Auth::id(),
                'customer_id' => null,
                'total_price' => $total,
                'bayar' => $total,
                'change' => 0,
            ]);

            DetailTransaction::create([
                'pembelian_id' => $pembelian->id,
                'product_id' => $product->id,
                'qty' => $jumlah,
                'price' => $product->price,
                'subtotal' => $total,
            ]);

            $product->decrement('stock', $jumlah);
        }
    }
}
