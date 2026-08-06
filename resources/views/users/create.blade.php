@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-user-plus"></i> Add New User</h5>
        <a href="{{ route('users.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
            <i class="fas fa-arrow-left me-1"></i> Back to Users List
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" onsubmit="triggerSaveUser(event)">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required autofocus>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required>
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="">-- Select Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                @error('role') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Triggering Save setting form
    function triggerSaveUser(event) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Saving user...',
                text: 'Please wait while a new user is being saved to database.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    }
</script>
@endsection