@extends('layouts.app')

@section('title', 'Edit Staff Schedule')

@section('content')
<div class="container-fluid">
    
    <div class="card shadow-sm border-0 rounded-3 max-w-4xl mx-auto">
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Edit Staff Schedule</h5>
            <a href="{{ route('staff-schedules.index') }}" class="btn btn-light btn-sm text-primary fw-bold shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Schedule 
            </a>
        </div>
        
        <div class="card-body p-4">
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                    <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Form-->
            <form action="{{ route('schedules.update', $schedule->id) }}" method="POST" onsubmit="triggerSaveLoader(event)">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <!-- Choose Staff Member -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">Assigned Staff (Housekeeper) <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select searchable-dropdown" required>
                            <option value="">Search housekeeper...</option>
                            @foreach($housekeepers as $staff)
                                <option value="{{ $staff->id }}" {{ $schedule->user_id == $staff->id ? 'selected' : '' }}>
                                    {{ $staff->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Choose Shift -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">Shift <span class="text-danger">*</span></label>
                        <select name="shift_id" class="form-select searchable-dropdown" required>
                            <option value="">Search shift...</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}" {{ $schedule->shift_id == $shift->id ? 'selected' : '' }}>
                                    {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Shift Date -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-dark">Shift Allocation Date <span class="text-danger">*</span></label>
                        <input type="date" name="shift_date" class="form-control fw-bold text-secondary font-monospace" value="{{ old('shift_date', $schedule->shift_date) }}" required>
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Explain reason for updating the schedule">{{ old('notes', $schedule->notes) }}</textarea>
                    </div>
                </div>

                <!-- Saving changes btn -->
                <div class="text-end mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2-bootstrap-5.min.css') }}" rel="stylesheet" />
<script src="{{ asset('js/select2.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Select2 dropdown 
    $('.searchable-dropdown').select2({
        placeholder: "Tafuta hapa...",
        allowClear: true,
        theme: "bootstrap-5",
        width: '100%'
    });
});

// Save process loader during submission trigger
function triggerSaveLoader(event) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Updating Schedule...',
            text: 'Please wait while a schedule is being updated.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
}
</script>
@endsection
