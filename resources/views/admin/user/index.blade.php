@extends('template.sidebar') 
@section('content') 

<div class="container">
    <a href="{{route('admin.dashboard')}}"> 
        <i class="ti ti-home" style="color: #858585; font-size: 1.2rem;"></i>
    </a>
    <small style="font-size: 15px; color: #858585;"> > User  </small>
    <div class="join d-flex align-items-center justify-content-between">
        <div class="content d-flex align-items-center" style="justify-content: space-between;">
            <h5 class="mt-4 mb-2"><b>User</b></h5>
        </div>
    </div>
  
    <div class="card p-4" style="width:960px;">
        <div class="row">
            <div class="d-flex justify-content-between align-items-center mb-4">  
                <a href="{{ route('admin.user.export') }}"  class="btn btn-outline-secondary btn-sm" style=" padding:7px; width:180px; font-size:100%">
                    Export Excel (.xlsx)
                </a>
                <a href="{{ route('admin.user.create') }}" class="btn" style="background:#0b44b6; color:white; padding:7px; width:180px; font-size:100%">
                    + Tambah User
                </a>       
            </div>
            <div id="content" class="tab-content">
                <table class="table table-sm">
                    <thead class="thead" style="align-items: flex-end; color:#858585; font-size:95%">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>                  
                            <th>Role</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="table-tbody" style="font-size:13px; color:#989898; vertical-align: middle;">
                        @php $no = 1; @endphp
                        @foreach ($users as $item)  
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item['name']}}</td>
                                <td>{{ $item['email']}}</td>
                                <td>{{ $item['role']}}</td>
                                <td style="gap: 10px; text-align :center;">
                                    <a href="{{route('admin.user.edit', $item->id)}}" class="btn btn-warning text-xs px-3 py-1" style="font-size: 13px; color:white">Edit</a>
                                    <button class="btn btn-danger text-xs px-2 py-1" style="font-size: 13px;" data-bs-toggle="modal" data-bs-target="#deletemodal{{$item->id}}">Hapus</button>
                                   <!-- Modal untuk konfirmasi hapus -->
                                    <div class="modal" tabindex="-1" id="deletemodal{{$item->id}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Yakin ingin menghapus data ini?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                                    
                                                    <form action="{{ route('admin.user.destroy', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
      </div>
    </div>
</div>
@endsection
