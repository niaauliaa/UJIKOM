@extends('template.sidebar') 
@section('content') 

<div class="container">
    <a href="{{ route('admin.dashboard') }}">
        <i class="ti ti-home text-secondary fs-5"></i>
    </a>
    <small class="fs-6">
        <a href="{{ route('admin.product.index') }}" class="text-secondary"> > Produk</a>
    </small>

    <div class="d-flex align-items-center justify-content-between mt-4 mb-3">
        <h5 class="mb-0 fw-bold">Edit Produk</h5>
    </div>

    <div class="card p-4" style="width:960px;">
        <div class="card-body">
            <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf  
                @method('PUT')     

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="name_product" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm @error('name_product') is-invalid @enderror" name="name_product" id="name_product" value="{{ old('name_product', $product->name_product) }}" style="font-size:13px; color:#858585;" > <!-- Menampilkan data lama -->
                        @error('name_product')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="picture" class="form-label">Gambar Produk <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control-sm @error('picture') is-invalid @enderror" name="picture" id="picture" style="color:#858585;" >
                        {{-- @if($product->picture)
                        <img src="{{ asset('images/'.$product->picture) }}" alt="Gambar Produk" width="100" class="mt-2">
                        @endif --}}
                        @error('picture')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror 
                    </div>

                    <div class="col-md-6 mb-2 position-relative">
                        <label for="price" class="form-label">Harga <span class="text-danger">*</span></label> 
                        <input type="text" class="form-control form-control-sm @error('price') is-invalid @enderror" id="rupiah" name="price" value="{{ old('price', 'Rp. '.number_format($product->price, 0, ',', '.')) }}" style="color:#858585;">
                        @error('price')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                                   
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-sm @error('stock') is-invalid @enderror" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" readonly style="background:#e8eee9"> <!-- Menampilkan data lama -->
                        @error('stock')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn" style="background: #0b44b6; color:white; padding:5px; margin-top:1%; width:110px; font-size:100%">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const priceInput = document.getElementById('rupiah');

    priceInput.addEventListener('input', function() {
        let value = priceInput.value.replace(/[^\d]/g, ''); 
        priceInput.value = 'Rp. ' + new Intl.NumberFormat('id-ID').format(value); 
    });

    document.querySelector('form').addEventListener('submit', function() {
        let value = priceInput.value.replace(/[^\d]/g, ''); 
        priceInput.value = value; 
    });
</script>

@endsection