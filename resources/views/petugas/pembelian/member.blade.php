@extends('template.sidebar')

@section('content')
<div class="container">
    <a href="{{ route('petugas.dashboard') }}">
        <i class="ti ti-home" style="color: #858585; font-size: 1.2rem;"></i>
    </a>
    <small style="font-size: 15px; color: #858585;"> > Penjualan</small>
    <h5 class="mt-4 mb-3"><b>Penjualan</b></h5>

    <div class="card p-4" style="width: 960px">
        <div class="row">
            <div class="col-md-6 mb-3">
                <table border="1" style="width: 450px; height:108%;">
                    <thead>
                        <tr>
                            <th style="padding: 10px;">Nama Produk</th>
                            <th style="padding: 10px;">QTY</th>
                            <th style="padding: 10px;">Harga</th>
                            <th style="padding: 10px;">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($checkoutData as $item)
                            @php
                                $subtotal = $item['produk']->price * $item['jumlah'];
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td style="padding: 10px;">{{ $item['produk']->name_product }}</td>
                                <td style="padding: 10px; text-align: center;">{{ $item['jumlah'] }}</td>
                                <td style="padding: 10px;">Rp{{ number_format($item['produk']->price, 0, ',', '.') }}</td>
                                <td style="padding: 10px;">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" style="padding: 10px; text-align: right; font-weight: bold;">Total Harga</td>
                            <td style="padding: 10px; font-weight: bold;">Rp{{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="padding: 10px; text-align: right; font-weight: bold;">Bayar</td>
                            <td style="padding: 10px; font-weight: bold;">Rp{{ number_format($bayar, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            @php
                            $isFirstPurchase = optional($member)->pembelian?->count() < 1;
                            $earnedPoints = floor($total * 0.01); // Menghitung poin yang didapatkan
                        @endphp    
                            <td colspan="3" style="padding: 10px; text-align: right; font-weight: bold;">Poin yang didapatkan:</td>
                            <td style="padding: 10px; font-weight: bold;">{{ number_format($earnedPoints, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <form action="{{ route('petugas.pembelian.memberstore')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Member</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $member->name ?? '') }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="point" class="form-label">Poin Member</label>
                        <input type="text" class="form-control" value="{{ $member->points ?? 0 }}" readonly style="background:#e8eee9">                       
                        {{-- <div class="mt-2">
                            <label class="form-label" style="font-size: 12px;">
                                Poin yang didapatkan: <b>{{ number_format($earnedPoints, 0, ',', '.') }} poin</b>
                            </label>
                        </div> --}}
                        <input class="form-check-input" type="checkbox" id="check_poin" name="use_points"
                            {{ $isFirstPurchase ? 'disabled' : '' }}>                     
                            <label class="form-check-label text-muted" for="check_poin" style="font-size: 12px">
                                Gunakan poin
                                <span class="text-danger" style="font-size: 11px">
                                    {{ $isFirstPurchase ? 'Poin tidak dapat digunakan pada pembelanjaan pertama.' : 'Centang jika ingin gunakan poin.' }}
                                </span>
                            </label>
                            <div id="estimasiDiskon" class="text-success mt-1" style="font-size: 12px; display:none;">
                                Estimasi potongan harga sebesar Rp{{ number_format($earnedPoints, 0, ',', '.') }}
                            </div>
                    </div>                  
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn" style="background: #0b44b6; color:white; padding:5px; margin-top:1%;  font-size:100%; width:100%;" id="simpanBtn">Simpan</button>
                    </div>
                </form>
                @push('scripts')
                <script>
                    const checkbox = document.getElementById('check_poin');
                    const estimasi = document.getElementById('estimasiDiskon');

                    checkbox?.addEventListener('change', function () {
                        if (this.checked) {
                            estimasi.style.display = 'block';
                        } else {
                            estimasi.style.display = 'none';
                        }
                    });
                </script>
                @endpush
            </div>
        </div>
    </div>
</div>
@endsection
