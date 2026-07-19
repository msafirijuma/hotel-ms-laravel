@extends('layouts.app')

@section('title', 'Edit Settings')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-cog me-2"></i>Edit Hotel's Settings</h5>
            <a href="{{ route('settings.show') }}" class="btn btn-light btn-sm text-primary fw-bold shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Details
            </a>
        </div>
        
        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5 class="mb-3 text-secondary border-bottom pb-2 fw-bold">Basic Information</h5>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hotel's Name <span class="text-danger">*</span></label>
                            <input type="text" name="hotel_name" class="form-control" value="{{ old('hotel_name', $settings->hotel_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tagline / Slogan</label>
                            <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $settings->tagline) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Address</label>
                            <textarea name="address" class="form-control" rows="3">{{ old('address', $settings->address) }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $settings->phone) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $settings->email) }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Website</label>
                                <input type="text" name="website" class="form-control" value="{{ old('website', $settings->website) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">TIN / Tax ID</label>
                                <input type="text" name="tin" class="form-control" value="{{ old('tin', $settings->tin) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Footer Message (Invoice/Receipt)</label>
                            <textarea name="footer_message" class="form-control" rows="3">{{ old('footer_message', $settings->footer_message) }}</textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <h5 class="mb-3 text-secondary border-bottom pb-2 fw-bold">Hotel's Logo</h5>
                        <div class="text-center mb-4 p-3 border rounded-3 bg-light">
                            @if($settings->logo_path)
                                <img src="{{ asset('storage/' . $settings->logo_path) }}" class="img-fluid rounded-3" style="max-height: 200px; object-fit: contain;">
                            @else
                                <div class="d-flex align-items-center justify-content-center text-muted" style="height: 200px;">
                                    <i class="fas fa-image fa-3x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Change Logo (Max 2MB)</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow fw-bold">
                        <i class="fas fa-save me-2"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
