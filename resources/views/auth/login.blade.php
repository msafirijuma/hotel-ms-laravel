@extends('layouts.app')

@section('title', 'Login - Hotel MS')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-lg-5 col-md-8">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <i class="bi bi-building-fill fs-1 text-primary"></i>
                        <h3 class="mt-3 fw-bold">Hotel Management System</h3>
                        <p class="text-muted">Sign in to your account</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success text-center">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Email or Phone Number</label>
                            <input type="text" name="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" 
                                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                                   required auto>
                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <a href="#" class="text-decoration-none small">Forgot Password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </button>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4 text-muted small">
                © {{ date('Y') }} Hotel MS
            </div>
        </div>
    </div>
</div>
@endsection