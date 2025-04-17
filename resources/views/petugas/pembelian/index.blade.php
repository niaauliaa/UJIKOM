@extends('template.sidebar')

@section('content') 
<div class="container">
    <a href="{{ route('petugas.dashboard') }}">
        <i class="ti ti-home" style="color:#858585; font-size: 1.2rem;"></i>
    </a>
    <small style="font-size:15px; color:#858585;"> > Penjualan</small>     
    <h5 class="mt-4 mb-2"><b>Penjualan</b></h5>
    
    <div class="card p-4" style="width: 1230px;">
        <div class="d-flex justify-content-between align-items-center mb-4">        
            <a href="{{ route('petugas.pembelian.export-excel') }}" class="btn btn-outline-secondary btn-sm" style=" padding:7px; width:180px; font-size:100%">Export Penjualan (.xlsx)</a>
            <a href="{{route('petugas.pembelian.create')}}" type="submit" class="btn" style="background: #0b44b6; color:white; padding:7px; width:180px; font-size:100%">+ Tambah Pembelian</a>   
        </div>

        <div class="table-responsive mb-4">
            <table id="myTable" class="table table-hover align-middle" style="font-size: 14px;">
                <thead style="background-color: #f0f0f0;  color:#858585;">
                    <tr>
                        <th>#</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal Penjualan</th>
                        <th>Total Harga</th>
                        <th>Dibuat Oleh</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody style="font-size:13px; color:#989898;">
                    @foreach ($pembelian as $index => $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->customer?->name ?? 'NON-MEMBER' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                        <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>
                            <button type="button" class="btn btn-sm" style="background-color: #ffc107; color: white;" data-bs-toggle="modal" data-bs-target="#modalPenjualan{{ $item->id }}">
                                Lihat
                            </button>
                            <a href="{{ route('petugas.exportPDF', $item->id) }}" target="_blank" class="btn btn-sm" style="background-color: #0d6efd; color: white;">
                                Unduh
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>    
        </div>
      
        @foreach ($pembelian as $item)
        <div class="modal fade" id="modalPenjualan{{ $item->id }}" tabindex="-1" aria-labelledby="modalPenjualanLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header" style="color:#858585;">
                        <h6 class="modal-title" id="modalPenjualanLabel{{ $item->id }}">Detail Penjualan</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="color:#989898; font-size:12px">
                        @if($item->customer)
                            <div class="row">
                                <div class="col">
                                    <p class="mb-1">Member Status: {{ $item->customer->member_status }}</p>
                                    <p class="mb-1">No. Hp: {{ $item->customer->phone_number }}</p>
                                    <p class="mb-01">Poin Member: {{ $item->customer->points }}</p>
                                </div>
                                <div class="col text-end">
                                    <p>Bergabung Sejak: {{ \Carbon\Carbon::parse($item->customer->joined_at)->format('d F Y') }}</p>
                                </div>
                            </div>
                        @else
                            <p><em>Non-Member</em></p>
                        @endif

                        <table style="width: 100%; margin-top: 10px; color:#858585;">
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
                                @foreach($item->details as $produk)
                                    @php
                                        $subtotal = $produk->qty * $produk->price;
                                        $total += $subtotal;
                                    @endphp
                                    <tr>
                                        <td style="padding: 10px;">{{ $produk->name_product }}</td>
                                        <td style="padding: 10px; text-align: center;">{{ $produk->qty }}</td>
                                        <td style="padding: 10px;">Rp{{ number_format($produk->price, 0, ',', '.') }}</td>
                                        <td style="padding: 10px;">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" style="padding: 10px; text-align: right; font-weight: bold;">Total</td>
                                    <td style="padding: 10px; font-weight: bold;">Rp{{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- <p class="mt-3 text-muted">Dibuat pada: {{ $item->created_at->format('Y-m-d H:i:s') }}<br>Oleh: {{ $item->user->name ?? '-' }}</p> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        const table = $('#myTable').DataTable({
            paging: true,
            searching: true,
            info: true,
            lengthChange: true, 
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)"
            }
        });

        // Tambahin jarak antara search & table
        $('.dataTables_filter').addClass('mb-3'); // search bar
        $('.dataTables_length').addClass('mb-3'); // dropdown "tampilkan x data"
    });
</script>
@endsection


@endsection


