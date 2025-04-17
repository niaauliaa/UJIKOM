@extends('template.sidebar') 
@section('content') 

<div class="container">
    <a href="{{ route('admin.dashboard') }}">
        <i class="ti ti-home text-secondary fs-5"></i>
    </a>
    <small class="fs-6">
        <a href="{{ route('admin.user.index') }}" class="text-secondary"> > User</a>
    </small>
    <div class="d-flex align-items-center justify-content-between mt-4 mb-3">
        <h5 class="mt-4 mb-2"><b>User</b></h5>
    </div>

    <div class="card p-4" style="width: 1230px;">
        <div class="card-body">
            <form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
                @csrf  

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="name" class="form-label">Nama <span style="color: red">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="" style="font-size:13px; color:#858585;">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email <span style="color: red">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" style="font-size:13px; color:#858585;">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="role" class="form-label">Role <span style="color: red">*</span></label>
                        <select class="form-control @error('role') is-invalid @enderror" name="role" id="role" style="font-size:13px; color:#858585;">
                            <option value="" disabled selected>-- Pilih Role --</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        </select>
                        @error('role')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span style="color: red">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" value="{{ old('password')}}" style="font-size:13px; color:#858585;">
                            @error('password')
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

@endsection 