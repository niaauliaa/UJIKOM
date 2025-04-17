@extends('template.sidebar')

@section('content') 
<div class="container pb-5" style="position: relative;">
    <a href="{{ route('petugas.dashboard') }}">
        <i class="ti ti-home" style="color: #858585; font-size: 1.2rem;"></i>
    </a>
    <small style="font-size: 15px; color: #858585;"> > Penjualan</small>
    <h5 class="mt-4 mb-3"><b>Tambah Penjualan</b></h5>

    <div class="card p-4">
        <div class="row">
            <div class="card-body">
                <form action="{{ route('petugas.pembelian.checkout')}}" method="POST">
                    @csrf
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @foreach ($products as $product)
                        <div class="col">
                            <div class="card h-100">
                                <div style="display: flex; justify-content: center; align-items: center;">
                                    <img src="{{ asset('images/' . $product['picture']) }}" class="card-img-top" style="width: 70%;" />
                                </div>            
                                <div class="card-body text-center">
                                    <h6><b>{{ $product['name_product'] }}</b></h6>
                                    <p id="stock-{{ $product->id }}">Stok: {{ $product['stock'] }}</p>
                                    <p>Rp <span class="price" id="price-{{ $product->id }}" data-price="{{ $product['price'] }}">{{ number_format($product['price'], 0, ',', '.') }}</span></p>

                                    <div class="d-flex justify-content-center align-items-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm minus" onclick="changeQuantity({{ $product->id }}, -1, {{ $product['stock'] }})">-</button>
                                        <span class="mx-3 jumlah" id="display-{{ $product->id }}">0</span>
                                        <button type="button" class="btn btn-outline-success btn-sm plus" onclick="changeQuantity({{ $product->id }}, 1, {{ $product['stock'] }})">+</button>
                                    </div>

                                    <p class="mt-2" style="font-size: 13px;">Sub Total <strong>Rp. <span class="subtotal" id="subtotal-{{ $product->id }}">0</span></strong></p>

                                    <input type="hidden" name="produk_id[]" value="{{ $product->id }}">
                                    <input type="hidden" name="jumlah[]" id="quantity-{{ $product->id }}" class="jumlah-input" value="0">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div style="
                        position: fixed;
                        bottom: 0;
                        left: 100px;
                        right: 0;
                        background: white;
                        padding: 15px 0;
                        box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
                        z-index: 999;
                        text-align: center;">
                        <button type="submit" class="btn btn-primary px-4">Selanjutnya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function changeQuantity(id, change, maxStock) {
        const input = document.getElementById('quantity-' + id);
        const stockEl = document.getElementById('stock-' + id);
        const display = document.getElementById('display-' + id);
        const priceEl = document.getElementById('price-' + id);
        const subtotalEl = document.getElementById('subtotal-' + id);

        let qty = parseInt(input.value) || 0;
        let stockNow = parseInt(stockEl.textContent.replace('Stok: ', '')) || 0;
        let price = parseInt(priceEl.dataset.price) || 0;

        let newQty = qty + change;
        let newStock = stockNow - change;

        if (change > 0 && stockNow <= 0) {
        alert('Stok habis!');
        return;
    }

        if (newQty < 0 || newQty > maxStock || newStock < 0) return;

        input.value = newQty;
        stockEl.textContent = 'Stok: ' + newStock;
        display.textContent = newQty;

        let subtotal = price * newQty;
        subtotalEl.textContent = subtotal.toLocaleString('id-ID');
    }
</script>
@endsection
