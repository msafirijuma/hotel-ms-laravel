@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="card shadow-sm w-75 mt-4">
    <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-user-edit"></i> Edit User: {{ $user->name }}</h5>
        <a href="{{ route('users.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
            <i class="fas fa-arrow-left"></i> Back to Users List
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Photo -->
            <div class="mb-4 text-center">
                @if($user && $user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" 
                            class="rounded-circle mb-3" width="140" height="140" style="object-fit: cover;">
                @else
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3" style="width:140px;height:140px">
                        <i class="fas fa-user fa-4x text-white"></i>
                    </div>
                @endif
                
                <label class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-camera"></i> Change Image
                    <input type="file" name="photo" class="d-none" accept="image/*">
                </label>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" 
                           class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" value="{{ $user->email }}" 
                           class="form-control @error('email') is-invalid @enderror" required>
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">-- Select Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" 
                                {{ $user->roles->contains('name', $role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" name="phone" value="{{ $user->phone }}" 
                           class="form-control @error('phone') is-invalid @enderror">
                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">New Password (Leave blank if not changing)</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection