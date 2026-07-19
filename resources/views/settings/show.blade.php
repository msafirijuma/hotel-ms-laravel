@extends('layouts.app')

@section('title', 'Hotel Profile')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-hotel me-2"></i>Hotel Profile & Information</h5>
            
            <!-- Edit settings -->
            <a href="{{ route('settings.edit') }}" class="btn btn-light btn-sm text-primary fw-bold shadow-sm">
                <i class="fas fa-edit me-1"></i> Edit Settings
            </a>
        </div>
        
        <div class="card-body p-4">
            <div class="row g-4">
                <!-- Logo and slogan -->
                <div class="col-lg-4 text-center border-end">
                    <div class="p-3 bg-light rounded-3 border mb-3 mx-auto" style="max-width: 250px;">
                        @if($settings->logo_path)
                            <img src="{{ asset('storage/' . $settings->logo_path) }}" class="img-fluid rounded-3" style="max-height: 180px; object-fit: contain;" alt="Hotel Logo">
                        @else
                            <div class="d-flex align-items-center justify-content-center text-muted" style="height: 150px;">
                                <i class="fas fa-image fa-4x"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ $settings->hotel_name }}</h4>
                    <p class="text-primary italic small fw-semibold">{{ $settings->tagline ?? '— No tagline set —' }}</p>
                </div>

                <!-- Official details -->
                <div class="col-lg-8 ps-lg-4">
                    <h5 class="text-secondary border-bottom pb-2 mb-3 fw-bold">
                        <i class="fas fa-info-circle text-primary me-2"></i>Official Details
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="text-muted d-block small text-uppercase fw-bold">Phone Number</span>
                            <span class="fs-6 text-dark fw-semibold">{{ $settings->phone ?? '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block small text-uppercase fw-bold">Email</span>
                            <span class="fs-6 text-dark fw-semibold">{{ $settings->email ?? '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block small text-uppercase fw-bold">Website</span>
                            <span class="fs-6 text-dark fw-semibold">
                                @if($settings->website)
                                    <a href="{{ $settings->website }}" target="_blank" class="text-decoration-none">{{ $settings->website }}</a>
                                @else — @endif
                            </span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted d-block small text-uppercase fw-bold">TIN / Tax ID</span>
                            <span class="fs-6 text-dark fw-semibold">{{ $settings->tin ?? '—' }}</span>
                        </div>
                        <div class="col-12">
                            <span class="text-muted d-block small text-uppercase fw-bold">Address</span>
                            <span class="fs-6 text-dark fw-semibold d-block p-2 bg-light rounded border mt-1">{{ $settings->address ?? '—' }}</span>
                        </div>
                        <div class="col-12">
                            <span class="text-muted d-block small text-uppercase fw-bold">Invoice Footer</span>
                            <span class="fs-6 text-muted italic d-block p-2 bg-light rounded border mt-1" style="font-size: 14px;">
                                "{{ $settings->footer_message ?? '—' }}"
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
