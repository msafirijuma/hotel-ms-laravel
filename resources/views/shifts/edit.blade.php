@extends('layouts.app')

@section('title', 'Edit Shift')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Edit Shift</h5>
            <a href="{{ route('shifts.index') }}" class="btn btn-light btn-sm text-primary fw-bold shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Shifts
            </a>
        </div>
        
        <div class="card-body p-4">
            <!-- Error handling -->
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

            <form action="{{ route('shifts.update', $shift->id) }}" method="POST" enctype="multipart/form-data" onsubmit="triggerSaveSettings(event)">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Shift Name -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark">Shift Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required 
                               value="{{ old('name', $shift->name) }}" placeholder="e.g., Night Shift">
                    </div>
                    
                    <!-- Start Time -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark">Start Time <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" class="form-control" required 
                               value="{{ old('start_time', \Carbon\Carbon::parse($shift->start_time)->format('H:i')) }}">
                    </div>
                    
                    <!-- End Time -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark">End Time <span class="text-danger">*</span></label>
                        <input type="time" name="end_time" class="form-control" required 
                               value="{{ old('end_time', \Carbon\Carbon::parse($shift->end_time)->format('H:i')) }}">
                    </div>
                </div>

                <div class="text-end mt-5 border-top pt-3">
                    <button type="submit" class="btn btn-warning btn-lg px-5 shadow text-dark fw-bold">
                        <i class="fas fa-save me-2"></i> Save Changes 
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Triggering Save setting form
    function triggerSaveSettings(event) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Saving changes...',
                text: 'Please wait while a shift is being saved.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    }
</script>
@endsection
