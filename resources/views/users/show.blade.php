@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm bg-primary mb-3 text-white p-3 d-flex justify-content-center align-items-center">
                    <h4><i class="fas fa-user"></i> <span class="fw-bold">Staff Profile - {{ $user->name }}</span> </h4>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Quick info -->
            <div class="col-lg-4">
                <div class="card shadow-sm text-center mb-4">
                    <div class="card-body">
                        @if($user && $user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" 
                                 class="rounded-circle mb-3" width="170" height="170" style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width:170px;height:170px">
                                <i class="fas fa-user fa-5x text-white"></i>
                            </div>
                        @endif
                        
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted mb-2">{{ $user->email }}</p>
                        
                        <!-- Role -->
                        <span class="badge bg-{{ 
                            $user->hasRole('admin') ? 'danger' : 
                            ($user->hasRole('receptionist') ? 'primary' : 
                            ($user->hasRole('housekeeper') ? 'success' : 'info')) 
                        }} px-3 py-2 text-uppercase">
                            {{$user->getRoleNames()->first() ?? 'User' }}
                        </span>
                    </div>

                </div>

                <!-- Action buttons -->
                <button onclick="triggerEditProfile('{{ route('users.edit', $user) }}')" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-edit"></i> Edit Profile
                </button>
                <button onclick="triggerChangePassword('{{ route('password.change') }}')" class="btn btn-outline-primary w-100">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </div>

            <!-- Staff Information -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Staff Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <span class="text-muted d-block small">Full Name</span>
                                <strong>{{ $user->name }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block small">Email</span>
                                <strong>{{ $user->email }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block small">Phone</span>
                                <strong>{{ $user->phone ?? '—' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block small">Role</span>
                                <strong>
                                {{ ucfirst($user->getRoleNames()->first() ?? 'User') }}
                                </strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block small"> Hired Since</span>
                                <strong>{{ $user->created_at ? $user->created_at->format('d M Y') : '—' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function showPageLoader(message) {
        Swal.fire({
            title: 'Please wait...',
            text: message,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // Change Password Loading
    function triggerChangePassword(url) {
        showPageLoader('Preparing update password form...');
        window.location.href = url;
    }

    // Edit Action Loading
    function triggerEditProfile(url) {
        showPageLoader('Preparing edit profile form...');
        window.location.href = url;
    }

</script>
@endsection
