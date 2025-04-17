<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Pembelian;
use App\Models\Customer;
use App\Models\DetailTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Imports\PembelianImport;
use App\Exports\PenjualanExport;
use Maatwebsite\Excel\Facades\Excel;

class PetugasController extends Controller
{   
    public function productIndex()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('petugas.product.index', compact('products'));
    }

    public function pembelianIndex()
    {
    $pembelian = Pembelian::with(['user', 'customer', 'details'])->latest()->get();
    return view('petugas.pembelian.index', compact('pembelian'));
    }

    public function pembelianCreate()
    {
        $products = Product::all();
        return view('petugas.pembelian.create', compact('products'));
    }

    public function pembelianCheckout(Request $request)
    {
        $produkIds = $request->input('produk_id');
        $jumlahs = $request->input('jumlah');

        $checkoutData = [];

        foreach ($produkIds as $key => $produkId) {
            $jumlah = $jumlahs[$key];
            if ($jumlah > 0) {
                $checkoutData[] = [
                    'produk_id' => $produkId,
                    'jumlah' => $jumlah,
                    'produk' => Product::find($produkId),
                ];
            }
        }

        Session::put('checkout_data', $checkoutData);

        return view('petugas.pembelian.checkout', compact('checkoutData'));
    }

    public function pembelianStore(Request $request)
    {
        $checkoutData = Session::get('checkout_data');
    
        if (!$checkoutData) {
            return redirect()->route('petugas.pembelian.create')->with('error', 'Data checkout tidak ditemukan.');
        }
    
        $request->validate([
            'bayar' => 'required|numeric|min:0',
            'produk_id' => 'required|array',
            'jumlah' => 'required|array',
        ]);
    
        session()->put('bayar', $request->bayar);
        session()->put('produk_id', $request->produk_id);
        session()->put('jumlah', $request->jumlah);
    
        if ($request->member_status === 'member') {
            $request->validate([
                'phone_number' => 'required|string',
            ]);
    
            $member = Customer::with('pembelian')
                ->where('phone_number', $request->phone_number)
                ->where('member_status', 'member')
                ->first();
    
            session()->put('phone_number', $request->phone_number);
            session()->put('member_status', 'member');
            session()->put('total_price', session('bayar'));
    
            $bayar = preg_replace('/[^0-9]/', '', session('bayar'));
    
            return view('petugas.pembelian.member', compact('checkoutData', 'bayar', 'member'));
        }
    
        $total = 0;
        foreach ($request->produk_id as $index => $produkId) {
            $product = Product::find($produkId);
            if ($product) {
                $jumlah = $request->jumlah[$index];
                $total += $product->price * $jumlah;
            }
        }
    
        $bayar = (int) preg_replace('/[^0-9]/', '', $request->bayar);
        $kembalian = $bayar - $total;
    
        $pembelian = Pembelian::create([
            'user_id' => Auth::id(),
            'customer_id' => null,
            'total_price' => $total,
            'bayar' => $bayar,
            'change' => $kembalian,
        ]);
    
        foreach ($checkoutData as $item) {
            DetailTransaction::create([
                'pembelian_id' => $pembelian->id,
                'product_id' => $item['produk']->id,
                'qty' => $item['jumlah'],
                'price' => $item['produk']->price,
                'subtotal' => $item['produk']->price * $item['jumlah'],
            ]);
            $item['produk']->decrement('stock', $item['jumlah']);
        }
    
        Session::forget(['checkout_data', 'bayar', 'produk_id', 'jumlah', 'phone_number', 'member_status']);
    
        return redirect()->route('petugas.pembelian.detail-print', $pembelian->id);
    }
    

    public function pembelianMemberStore(Request $request)
    {
        $checkoutData = Session::get('checkout_data');
        if (!$checkoutData) {
            return redirect()->route('petugas.pembelian.create')->with('error', 'Data checkout tidak ditemukan.');
        }
    
        $notelp = session('phone_number');
        $bayar = intval(preg_replace('/[^0-9]/', '', session('bayar')));
    
        $request->validate([
            'name' => 'required|string',
        ]);
    
        $customer = Customer::firstOrCreate(
            ['phone_number' => $notelp],
            ['name' => $request->name, 'joined_at' => now()]
        );
    
        if (!$customer->wasRecentlyCreated) {
            $customer->update(['name' => $request->name]);
        }
    
        $subtotal = 0;
        foreach ($checkoutData as $item) {
            $subtotal += $item['produk']->price * $item['jumlah'];
        }
    
        $total = $subtotal;
    
        $isNotFirstPurchase = $customer->pembelian()->exists();
        $availablePoints = $customer->points;
        $usedPoints = 0;
    
        if ($isNotFirstPurchase && $request->has('use_points') && $availablePoints > 0) {
            $usedPoints = min($availablePoints, $total);
            $total -= $usedPoints;
        }
    
        $kembalian = $bayar - $total;
    
        $pointsEarned = round($subtotal * 0.01);
    
        $customer->update([
            'points' => ($availablePoints - $usedPoints) + $pointsEarned
        ]);
    
        $pembelian = Pembelian::create([
            'user_id' => Auth::id(),
            'customer_id' => $customer->id,
            'total_price' => $subtotal, 
            'bayar' => $bayar,
            'change' => $kembalian,
            'points' => $pointsEarned,
            'used_points' => $usedPoints,
        ]);
    
        foreach ($checkoutData as $item) {
            DetailTransaction::create([
                'pembelian_id' => $pembelian->id,
                'product_id' => $item['produk']->id,
                'qty' => $item['jumlah'],
                'price' => $item['produk']->price,
                'subtotal' => $item['produk']->price * $item['jumlah'],
            ]);
            $item['produk']->decrement('stock', $item['jumlah']);
        }
    
        Session::forget(['checkout_data', 'bayar', 'produk_id', 'jumlah', 'phone_number', 'member_status']);
    
        return redirect()->route('petugas.pembelian.detail-print', $pembelian->id);
    }
    

    public function pembelianDetailPrint($id)
    {
        $pembelian = Pembelian::with(['user', 'customer'])->findOrFail($id);
        return view('petugas.pembelian.detail-print', compact('pembelian'));
    }

    public function exportPDF($id)
    {
        $pembelian = Pembelian::with(['user', 'customer', 'details.produk'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.pdf', compact('pembelian'));
        return $pdf->download('pembelian-' . $pembelian->id . '.pdf');
    }
    
    public function exportExcel()
    {
        return Excel::download(new PenjualanExport, 'penjualan.xlsx');
    }

    public function dashboard()
    {
        $total_transactions = Pembelian::count();
        $last_transactions = Pembelian::latest()->first();
        return view('petugas.dashboard', compact('total_transactions','last_transactions'));
    }

}
