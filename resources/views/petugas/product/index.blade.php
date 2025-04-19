@extends('template.sidebar') 
@section('content') 

<div class="container">
    <a href="{{ route('petugas.dashboard') }}">
        <i class="ti ti-home" style="color: #858585; font-size: 1.2rem; "></i>
    </a>
    <small style="font-size: 15px; color: #858585;">> Produk</small>
    <div class="join d-flex align-items-center justify-content-between">
        <div class="content d-flex align-items-center" style="justify-content: space-between;">
            <h5 class="mt-4 mb-2"><b>Produk</b></h5>
        </div>
    </div>
  
    <div class="card p-4" style="width:960px">
        <div class="row">
            <div id="content" class="tab-content">
                <table class="table table-sm">
                    <thead class="thead" style="align-items: flex-end; color:#858585; font-size:95%">
                        <tr>
                            <th>#</th>
                            <th style="width: 120px;"></th> 
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody class="table-tbody" style="font-size:13px; color:#989898; vertical-align: middle;">
                        @php $no = 1; @endphp
                        @foreach ($products as $item)  
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td style="text-align: center; vertical-align: middle;"> 
                                    <img src="{{ asset('images/' . $item->picture) }}" alt="{{ $item->name_product }}" width="100">
                                </td>
                                <td>{{ $item['name_product']}}</td>
                                <td>{{ 'Rp. ' . number_format($item['price'], 0, ',', '.') }}</td>
                                <td>{{ $item['stock']}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>              
            </div>
        </div>
    </div>
</div>

@endsection


