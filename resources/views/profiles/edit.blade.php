@extends('layouts.app')

@section('title', 'Edit My Profile')

@section('content')
    <div class="container">        
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Edit My Profile</h5>
                        <a href="{{ route('my-profile') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                            <i class="fas fa-arrow-left"></i> Back to Profile
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
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
                                    <i class="fas fa-camera"></i> Change Password
                                    <input type="file" name="photo" class="d-none" accept="image/*">
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Simu <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" 
                                               value="{{ old('phone', $user->phone) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Birth date</label>
                                        <input type="date" name="date_of_birth" class="form-control" 
                                               value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Role</label>
                                        <input type="text" class="form-control" 
                                               value="{{ ucfirst(auth()->user()->getRoleNames()->first()) ?? '—' }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                                <a href="{{ route('my-profile') }}" class="btn btn-secondary btn-lg">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection