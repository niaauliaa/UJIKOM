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
        <h5 class="mt-4 mb-2"><b>Produk</b></h5>
    </div>

    <div class="card p-4" style="width: 1230px;">
        <div class="card-body">
            <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf  

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="name_product" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm @error('name_product') is-invalid @enderror" name="name_product" id="name_product" value="{{ old('name_product') }}" style="font-size:13px; color:#858585;">
                        @error('name_product')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="picture" class="form-label">Gambar Produk <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control-sm @error('picture') is-invalid @enderror" name="picture" id="picture" style="font-size:13px; color:#858585;">
                        @error('picture')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm @error('price') is-invalid @enderror" name="price" id="rupiah" value="{{ old('price') }}" style="font-size:13px; color:#858585;">
                        @error('price')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-sm @error('stock') is-invalid @enderror" name="stock" id="stock" value="{{ old('stock') }}" style="font-size:13px; color:#858585;"
                        onkeydown="return event.key !== 'e' && event.key !== 'E' && event.key !== '+' && event.key !== '-'">
                        @error('stock')
                            <div class="text-danger small">{{ $message }}</div>
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
    // Menampilkan simbol "Rp." dan pemisah ribuan
    priceInput.addEventListener('input', function() {
        let value = priceInput.value.replace(/[^\d]/g, ''); // Hapus karakter selain angka
        priceInput.value = 'Rp. ' + new Intl.NumberFormat('id-ID').format(value); // Format dengan pemisah ribuan
    });

    // Sebelum form disubmit, hapus simbol "Rp." dan titik agar Laravel bisa memprosesnya sebagai angka
    document.querySelector('form').addEventListener('submit', function() {
        let value = priceInput.value.replace(/[^\d]/g, ''); // Hapus simbol "Rp." dan titik
        priceInput.value = value; // Kirim nilai yang hanya berisi angka ke server
    });
</script>

@endsection
