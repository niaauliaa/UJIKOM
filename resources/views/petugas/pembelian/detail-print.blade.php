@extends('template.sidebar')

@section('content')
<div class="container">
    <a href="{{ route('petugas.dashboard') }}">
        <i class="ti ti-home" style="color: #858585; font-size: 1.2rem;"></i>
    </a>
    <small style="font-size: 15px; color: #858585;"> > Pembayaran</small>
    <h5 class="mt-4 mb-3"><b>Pembayaran</b></h5>
    
    <div class="card p-4" style="width:960px">
        <div class="d-flex justify-content-between align-items-center mb-3">    
            <div>
                <a href="{{ route('petugas.exportPDF', $pembelian->id) }}" target="_blank" class="btn btn-sm" style="background-color: #0d6efd; color: white;">
                    Unduh
                </a>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('petugas.pembelian.index') }}'"style="width:80px; font-size:14px; padding:5px;">Kembali</button>
            </div>
            <div style="color:#858585;">
                <p class="mb-0">Invoice - #{{ $pembelian->id }}</p>
                <p class="mb-0">{{ \Carbon\Carbon::parse($pembelian->created_at)->format('d F Y') }}</p>  
            </div>
        </div>

        @if($pembelian->customer)
        <div style="color:#656565;" class="mb-3">
            <div><strong>{{ $pembelian->customer->phone_number }}</strong></div>
            <div>MEMBER SEJAK : {{ \Carbon\Carbon::parse($pembelian->customer->joined_at)->format('d F Y') }}</div>
            <div>POIN SAAT INI : {{ $pembelian->customer->points }}</div>
        </div>
        @endif

        <table class="table mt-4">
            <thead style="color:#858585;">
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Quantity</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody style="color:#989898">
                @foreach($pembelian->details as $item)
                <tr>
                    <td>{{ $item->produk->name_product }}</td>
                    <td>Rp. {{ number_format($item->produk->price, 0, ',', '.') }}</td>
                    <td>{{ $item->qty }}</td> 
                    <td>Rp. {{ number_format($item->produk->price * $item->qty, 0, ',', '.') }}</td> 
                </tr>
                @endforeach          
            </tbody>
        </table>

        <div class="d-flex mt-4 rounded overflow-hidden" style="min-height: 70px;">
            <div class="d-flex justify-content-start align-items-center bg-light py-3 px-4" style="width: 100%;">
                <div>
                    <small class="text-muted text-uppercase d-block" style="font-size: 12px;">Poin Digunakan</small>
                    <div class="fw-bold text-secondary fs-5">
                        {{ $pembelian->used_points ?? 0 }}
                    </div>
                </div>
                
                <div class="mx-5">
                    <small class="text-muted text-uppercase d-block" style="font-size: 12px;">Kasir</small>
                    <div class="fw-bold text-secondary fs-5">{{ Auth::user()->name }}</div>
                </div>
                
                <div class="mx-5">
                    <small class="text-muted text-uppercase d-block" style="font-size: 12px;">Kembalian</small>
                    <div class="fw-bold text-secondary fs-5">
                        Rp. {{ number_format($pembelian->change, 0, ',', '.') }}
                    </div>
                </div>                
            </div>

            <div class="bg-dark text-white d-flex flex-column justify-content-between px-4 py-2" style="min-width: 220px;">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <small class="text-secondary text-uppercase fw-bold " style="font-size: 12px;">Total</small>
                </div>
                <div class="d-flex flex-column align-items-end">
                    @if($pembelian->used_points > 0)
                        <div class="fs-6 text-decoration-line-through text-white">
                            Rp. {{ number_format($pembelian->total_price, 0, ',', '.') }}
                        </div>
                        <div class="fs-5 fw-bold">
                            Rp. {{ number_format($pembelian->total_price - $pembelian->used_points, 0, ',', '.') }}
                        </div> 
                    @else
                        <div class="fs-5 fw-bold">
                            Rp. {{ number_format($pembelian->total_price, 0, ',', '.') }}
                        </div> 
                    @endif
                </div>
            </div>
        </div>            

        <div class="mt-4">
            @if($pembelian->is_first_purchase)
                <div class="alert alert-warning">
                    <strong>Perhatian!</strong> Pembelian pertama tidak dapat menggunakan poin.
                </div>
            {{-- @else
                <div class="alert alert-info">
                    Poin yang didapat: {{ number_format($pembelian->points, 0, ',', '.') }} Poin
                </div> --}}
            @endif
        </div>
    </div>   
</div>
@endsection
