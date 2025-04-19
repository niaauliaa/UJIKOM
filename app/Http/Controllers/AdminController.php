<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Imports\PembelianImport;
use App\Exports\PenjualanExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use App\Exports\UserExport;

class AdminController extends Controller
{

    public function dashboard()
    {
        $barChartData = Pembelian::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereDate('created_at', '>=', now()->subDays(6))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

            $barLabels = [];
            $barValues = [];
            
            $startDate = now()->subDays(6)->startOfDay();
            $endDate = now()->endOfDay();
            $allDates = collect();
            
            for ($date = $startDate; $date <= $endDate; $date->addDay()) {
                $allDates->push($date->copy());
            }
            
            foreach ($allDates as $date) {
                $label = $date->translatedFormat('d F Y');
                $barLabels[] = $label;
            
                $match = $barChartData->firstWhere('tanggal', $date->toDateString());
                $barValues[] = $match ? $match->jumlah : 0;
            }
            
        $pieChartData = DB::table('detail_transactions')
            ->join('products', 'detail_transactions.product_id', '=', 'products.id')
            ->join('pembelians', 'detail_transactions.pembelian_id', '=', 'pembelians.id')
            ->whereDate('pembelians.created_at', '>=', now()->subDays(6)) // ambil data dari 7 hari terakhir termasuk hari ini
            ->select('products.name_product', DB::raw('SUM(detail_transactions.qty) as total'))
            ->groupBy('products.name_product')
            ->get();

        $pieLabels = $pieChartData->pluck('name_product');
        $pieValues = $pieChartData->pluck('total');
    
        return view('admin.dashboard', compact('barLabels', 'barValues', 'pieLabels', 'pieValues'));
    }
    
    public function productIndex()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('admin.product.index', compact('products'));
    }

    public function productCreate()
    {
        return view('admin.product.create');
    }

    public function productStore(Request $request)
    {
        $request->merge([
            'price' => preg_replace('/[^\d]/', '', $request->price)
        ]);

        $request->validate([
            'name_product' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('picture')) {
            $imageName = time() . '.' . $request->picture->extension();
            $request->picture->move(public_path('images'), $imageName);
        }

        Product::create([
            'name_product' => $request->name_product,
            'picture' => $imageName,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return redirect()->route('admin.product.index')->with('success', 'Product created successfully.');
    }

    public function productEdit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.product.edit', compact('product'));
    }

    public function productUpdateStok(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);
    
        $product->update([
            'stock' => $request->stock,
        ]);
    
        return redirect()->route('admin.product.index')->with('success', 'Stok produk berhasil diperbarui.');
    }

    public function productUpdate(Request $request, Product $product)
    {
        $request->merge([
            'price' => str_replace(['Rp.', 'Rp', ' ', '.'], '', $request->price)
        ]);

        $request->validate([
            'name_product' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = $product->picture;

        if ($request->hasFile('picture')) {
            if ($imageName && File::exists(public_path('images/' . $imageName))) {
                File::delete(public_path('images/' . $imageName));
            }

            $imageName = time() . '.' . $request->picture->extension();
            $request->picture->move(public_path('images'), $imageName);
        }

        $product->update([
            'name_product' => $request->name_product,
            'price' => $request->price,
            'stock' => $request->stock,
            'picture' => $imageName,
        ]);

        return redirect()->route('admin.product.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function productDestroy(Product $product)
    {
        if ($product->picture && File::exists(public_path('images/' . $product->picture))) {
            File::delete(public_path('images/' . $product->picture));
        }

        $product->delete();

        return redirect()->route('admin.product.index')->with('success', 'Product deleted successfully.');
    }

    public function userIndex()
    {
        $users = User::all(); 
        return view('admin.user.index', compact('users')); 
    }

    public function userCreate()
    {
        return view('admin.user.create');
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required|string',
            'password' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password), 
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User created successfully.');
    }

    public function userEdit($id)
    {
        $user = User::find($id); 
        return view('admin.user.edit', compact('user')); 
    }

    public function userUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required|string',
        ]);

        $user = User::find($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User updated successfully.');
    }

    public function productStok(Request $request, $id){
        $request->validate([
            'stock' => 'required|integer|min:1',
        ]);

        $stock = Product::find($id);

        $stock->update([
            'stock' => $request->stock,
        ]);

        return redirect()->route('admin.product.index')->with('success', 'User updated successfully.');
    }

    public function userDestroy($id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('admin.user.index')->with('success', 'User deleted successfully.');
    }

    public function pembelianIndex(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); 

        $query = Pembelian::with(['user', 'customer']);

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $pembelian = $query->latest()->paginate($perPage);
        $pembelian->appends(['search' => $search, 'per_page' => $perPage]);

        return view('admin.pembelian.index', compact('pembelian', 'search', 'perPage'));
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

    public function exportProductExcel()
    {
    return Excel::download(new ProductExport, 'products.xlsx');
    }

    public function exportUsersExcel()
    {
    return Excel::download(new UserExport, 'User.xlsx');
    }
 


}
