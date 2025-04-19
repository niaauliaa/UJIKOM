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
        <h5 class="mt-4 mb-2"><b>Edit User</b></h5>
    </div>

    <div class="card p-4" style="width:960px;">
        <div class="card-body">
            <form action="{{ route('admin.user.update', $user->id) }}" method="POST" enctype="multipart/form-data" >
                @csrf  
                @method('PUT') 

               <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $user->name) }}" style="font-size:13px; color:#858585; ">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email', $user->email) }}" style="font-size:13px; color:#858585; ">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-2 position-relative">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-control @error('role') is-invalid @enderror" name="role" id="role" style="font-size:13px; color:#858585;">
                            <option value="" disabled {{ old('role', $user->role) == '' ? 'selected' : '' }}>-- Pilih Role --</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        </select>
                        @error('role')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                                 
                    <div class="col-md-6 mb-3">                    
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" style="font-size:13px; color:#858585;">
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