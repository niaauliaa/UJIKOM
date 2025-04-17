@extends('template.sidebar') 
@section('content') 

<div class="container">
    <a href="{{route('admin.dashboard')}}"> 
        <i class="ti ti-home" style="color: #858585; font-size: 1.2rem;"></i>
    </a>
    <small style="font-size: 15px; color: #858585;"> > Produk  </small>
    <div class="join d-flex align-items-center justify-content-between">
        <div class="content d-flex align-items-center" style="justify-content: space-between;">
            <h5 class="mt-4 mb-2"><b>Produk</b></h5>
        </div>
    </div>
  
    <div class="card p-4" style="width: 1210px;">
        <div class="row">
                <div class="d-flex justify-content-between align-items-center mb-4">  
                    <a href="{{ route('admin.product.export') }}"  class="btn btn-outline-secondary btn-sm" style=" padding:7px; width:180px; font-size:100%">
                        Export Excel
                    </a>
                    <a href="{{ route('admin.product.create') }}" class="btn" style="background:#0b44b6; color:white; padding:7px; width:180px; font-size:100%">
                        + Tambah Produk
                    </a>
            </div>
            <div id="content" class="tab-content">
                <table class="table table-sm">
                    <thead class="thead" style="align-items: flex-end; color:#858585; font-size:95%">
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th>Nama Produk</th>                  
                            <th>Harga</th>
                            <th>Stok</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody class="table-tbody" style="font-size:13px; color:#989898; vertical-align: middle;">
                        @php $no = 1; @endphp
                        @foreach ($products as $item)  
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    <img src="{{ asset('images/' . $item->picture) }}" alt="{{ $item->name_product }}" width="100">
                                </td>
                                <td>{{ $item['name_product']}}</td>
                                <td>{{ 'Rp.' . number_format($item['price'], 0, ',', '.') }}</td>
                                <td>{{ $item['stock']}}</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <div style="display: flex; justify-content: center; gap: 5px;">
                                        <a href="{{ route('admin.product.edit', $item->id) }}" class=" btn btn-warning text-xs px-3 py-1" style="font-size: 13px; color:white; border-radius: 5px;">
                                            Edit
                                        </a>

                                        <button class="btn btn-info text-xs px-3 py-1" data-bs-toggle="modal" data-bs-target="#updateModal{{$item->id}}" style="font-size: 13px; color:white; border-radius: 5px;">
                                            Update Stok
                                        </button>

                                        <button class="btn btn-danger text-xs px-3 py-1" data-bs-toggle="modal" data-bs-target="#deletemodal{{$item->id}}" style="font-size: 13px; color:white; border-radius: 5px;">
                                            Hapus
                                        </button>
                                    </div>
                                </td>                                 
                                    
                                    <div class="modal fade" tabindex="-1" id="deletemodal{{$item->id}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Yakin ingin menghapus data ini?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                                    <form action="{{ route('admin.product.delete', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="updateModal{{$item->id}}" tabindex="-1" aria-labelledby="updateModalLabel{{$item->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateModalLabel{{$item->id}}">Update Stok Produk</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('admin.product.stok', $item->id)}}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="name_product" class="col-form-label text-start d-block">Nama Produk <span style="color:red">*</span></label>
                                                            <input type="text" class="form-control" name="name_product" value="{{ $item['name_product'] }}" readonly style="background:#e8eee9" >
                                                           
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="stock" class="col-form-label text-start d-block">Stok <span style="color:red">*</span></label>
                                                            <input type="number" class="form-control" name="stock" value="{{ $item['stock']}}" required>
                                                            {{-- <input type="number" class="form-control" name="stock" value="{{ $item['stock']}}" required min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '');"> --}}
                                                            @error('stock')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn" style="background: #0b44b6; color:white">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
