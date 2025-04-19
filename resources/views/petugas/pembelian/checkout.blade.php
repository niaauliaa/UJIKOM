@extends('template.sidebar')

@section('content')
<div class="container">
    <a href="{{ route('petugas.dashboard') }}">
        <i class="ti ti-home" style="color: #858585; font-size: 1.2rem;"></i>
    </a>
    <small style="font-size: 15px; color: #858585;"> > Penjualan</small>
    <h5 class="mt-4 mb-2"><b>Penjualan</b></h5>

    <div class="card p-4" style="width:960px">
        <h5 class="mb-4">Produk yang Dipilih</h5>  

        <div class="row">
            <div class="col-md-7">
                @php
                    $total = 0;
                @endphp

                @foreach($checkoutData as $item)
                    @php
                        $subtotal = $item['produk']->price * $item['jumlah'];
                        $total += $subtotal;
                    @endphp

                    <div class="d-flex justify-content-between align-items-start py-2 border-bottom" style="margin-right:30%">
                        <div>
                            <div style="color:#858585;">{{ $item['produk']->name_product }}</div>
                            <small class="text-muted">
                                Rp{{ number_format($item['produk']->price, 0, ',', '.') }} x {{ $item['jumlah'] }}
                            </small>
                        </div>
                        <div class="fw-semibold" style="color:#858585;">
                            Rp{{ number_format($subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-between mt-3 fw-bold" style="margin-right:30%; color:#858585;">
                    <h5><b>Total</b></h5>
                    <h5><b>Rp{{ number_format($total, 0, ',', '.') }}</b></h5>
                </div>
            </div>

            <div class="col-md-5 mt-md-0 mt-4">
                <form action="{{ route('petugas.pembelian.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="role" class="form-label"> Member Status
                             <span class="text-danger">Dapat juga membuat member</span>
                        </label>
                        <select class="form-control @error('role') is-invalid @enderror" name="member_status" id="role" style="font-size:13px; color:#858585;">
                            <option selected disabled>--pilih--</option>
                            <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                            <option value="non_mem" {{ old('role') == 'non_mem' ? 'selected' : '' }}>Non-member</option>
                        </select>
                        @error('role')
                            <span class="text-danger">{{$message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3" id="phone-field" style="display: none;">
                        <label for="phone_number" class="form-label"> Nomor Telepon
                            <span class="text-danger">(daftar/gunakan member)</span>  
                        </label>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" id="phone_number" value="{{ old('phone_number')}}" style="font-size:13px; color:#858585;">
                        @error('phone_number')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bayar" class="form-label">Total Bayar</label>
                        <input type="text" class="form-control" name="bayar" id="bayar" placeholder="Masukkan jumlah uang" style="font-size:13px; color:#858585;">
                        <p id="peringatan" class="text-danger d-none mt-1" style="font-size:13px;">Uang tidak cukup!</p>
                    </div>
                    
                    @foreach($checkoutData as $item)
                        <input type="hidden" name="produk_id[]" value="{{ $item['produk']->id }}">
                        <input type="hidden" name="jumlah[]" value="{{ $item['jumlah'] }}">
                    @endforeach

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn" style="background: #0b44b6; color:white; padding:5px; margin-top:1%; width:110px; font-size:100%" id="simpanBtn">Simpan</button>
                    </div>   
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const phoneField = document.getElementById('phone-field');
    const phoneInput = document.getElementById('phone_number');
    const priceInput = document.getElementById('bayar');  // Ubah ini
    const simpanBtn = document.getElementById('simpanBtn');
    const warning = document.getElementById('peringatan');

    const totalHarga = {{ $total }};

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'decimal',
            minimumFractionDigits: 0
        }).format(angka);
    }

    function cekBayarDanFormat() {
        let inputValue = priceInput.value.replace(/\D/g, '');
        const bayar = parseInt(inputValue || 0);

        priceInput.value = formatRupiah(bayar);

        if (bayar >= totalHarga) {
            simpanBtn.disabled = false;
            warning.classList.add('d-none');
        } else {
            simpanBtn.disabled = true;
            warning.classList.remove('d-none');
        }
    }

    roleSelect.addEventListener('change', function () {
        if (this.value === 'member') {
            phoneField.style.display = 'block';
        } else {
            phoneField.style.display = 'none';
            phoneInput.value = '';
        }
    });

    if (roleSelect.value === 'member') {
        phoneField.style.display = 'block';
    }

    priceInput.addEventListener('input', cekBayarDanFormat);
});

</script>
@endsection
