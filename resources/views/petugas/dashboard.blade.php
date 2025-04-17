@extends('template.sidebar') 
@section('content') 

@if (session('success'))
    <script>
        Swal.fire({
            title: 'Selamat Datang!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
            customClass: {
            popup: 'small-swal'
        }
        });
    </script>
@endif

<div class="container">
  <link rel="stylesheet" href="{{asset('Assets/css/alert.css')}}">
    <a href="{{route('petugas.dashboard')}}"> <i class="ti ti-home" style="color: #858585; font-size: 1.2rem; "></i></a>
    <small style="font-size: 15px; color:#858585;"> > Dashboard  </small>  
    <div class="join d-flex align-items-center justify-content-between">
        <div class="content d-flex align-items-center" style="justify-content: space-between;">
            <h5 class="mt-4 mb-2"><b>Dashboard</b></h5>
        </div>
    </div>

    <div class="card p-4" style="width: 1230px;">
      <div class="card text-center">
            <div class="card-header">
              Total penjualan hari ini
            </div>
            <div class="card-body">
              <h5 class="card-title">{{ $total_transactions }}</h5>
              <p class="card-text">Jumlah total penjualan yang terjadi hari ini</p>
            </div>
            <div class="card-footer text-body-secondary">
              @if ($last_transactions)
                  Terakhir diperbarui: {{ $last_transactions->created_at->format('d M Y H:i') }}
              @else
                  Tidak ada transaksi terbaru.
              @endif
          </div>       
    </div>
</div>

<script>

</script>

@endsection
