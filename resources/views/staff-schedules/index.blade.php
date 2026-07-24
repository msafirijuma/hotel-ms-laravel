@extends('layouts.app')

@section('title', 'Staff Scheduling')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0 rounded-3 mb-5">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2"></i>Schedule Staff by Shift (Multi-Date Range)</h5>
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

            <form action="{{ route('staff-schedules.store') }}" method="POST" enctype="multipart/form-data" onsubmit="triggerSaveChanges(event)">
                @csrf
                <div class="row g-4">
                    <!-- Choose Housekeeper -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">Choose Staff (Housekeeper)</label>
                        <select name="user_id" class="form-select searchable-dropdown" required>
                            <option value="">Search housekeeper...</option>
                            @foreach($housekeepers as $staff)
                                <option value="{{ $staff->id }}" {{ old('user_id') == $staff->id ? 'selected' : '' }}>
                                    {{ $staff->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Choose Shift -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">Choose Shift</label>
                        <select name="shift_id" class="form-select searchable-dropdown" required>
                            <option value="">Search shift...</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                                    {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Starting Date -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">Starting Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', date('Y-m-d')) }}" required>
                    </div>

                    <!-- Ending Date -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">Ending Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', date('Y-m-d', strtotime('+7 days'))) }}" required>
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark">Notes (optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Example: Staff member will be covering extra shift this week">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="text-end mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow">
                        <i class="fas fa-calendar-check me-2"></i> Save Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Today's schedules -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-clock me-2"></i>Today's Schedule ({{ date('d/m/Y') }})</h5>
        </div>
        <div class="card-body">
            @if($schedules && $schedules->count() > 0)
                <div class="table-responsive">
                    <!-- Datatable -->
                    <table class="table table-striped table-hover table-bordered align-middle mb-0" id="staffTable" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Housekeeper</th>
                                <th>Shift Assigned</th>
                                <th>Date</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $sched)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong class="text-dark">{{ $sched->user->name ?? 'N/A' }}</strong></td>
                                <td>
                                    <span class="badge bg-info text-dark px-3 py-1.5 fw-bold">
                                        {{ $sched->shift->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-secondary font-monospace">{{ \Carbon\Carbon::parse($sched->shift_date)->format('d/m/Y') }}</td>
                                <td class="text-muted"><small>{{ $sched->notes ?? '-' }}</small></td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center align-items-center">
                                        
                                         <a href="{{ route('schedules.edit', $sched->id) }}" onclick="triggerEdit(event, this.href)" class="btn btn-sm btn-warning py-1" title="Edit Schedule">
                                            <i class="fas fa-edit"></i>
                                        </a>
        
                                        <!-- 1. Secure Delete Form (Hidden) -->
                                        <form id="delete-schedule-form-{{ $sched->id }}" action="{{ route('schedules.destroy', $sched->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <!-- 2. Delete Schedule button -->
                                        <button type="button" onclick="triggerDelete({{ $sched->id }}, '{{ addslashes($sched->user->name ?? 'Staff') }}', '{{ \Carbon\Carbon::parse($sched->shift_date)->format('d/m/Y') }}')" class="btn btn-sm btn-danger py-1" title="Delete Schedule">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-calendar-times fa-4x mb-3 text-secondary"></i>
                    <h5 class="fw-bold">No Schedule for Today</h5>
                    <p class="small">Add a schedule above to get started.</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@section('scripts')


<script>
$(document).ready(function() {
    $('.searchable-dropdown').select2({
        placeholder: "Search here...",
        allowClear: true,
        theme: "bootstrap-5", 
        width: '100%'
    });
});
</script>
@section('scripts')
<script>
    // Loader function during page navigation
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

    // Triggering Save changes form
    function triggerSaveChanges(event) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Saving Schedule...',
                text: 'Please wait while saving staff schedule.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    }

     // Handle schedule edit layout loading event intercept
    function triggerEdit(event, url) {
        event.preventDefault(); 
        showPageLoader('Loading schedule...');
        window.location.href = url;
    }

    // Handle deletion of a specific staff shift schedule
    function triggerDelete(id, staffName, shiftDate) {
        Swal.fire({
            title: 'Remove from schedule?',
            text: `You are about to remove ${staffName} from assigned shift duty on ${shiftDate}!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Yes, Remove Duty!',
            cancelButtonText: 'No, Cancel',
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-danger btn-lg px-4 me-2 fw-bold shadow-sm',
                cancelButton: 'btn btn-secondary btn-lg px-4 fw-bold shadow-sm'
            },
            buttonsStyling: false
        }).then((result) => {
            // Loader during the deletion process
            if (result.isConfirmed) {
                showPageLoader('Removing staff schedule...');
                document.getElementById('delete-schedule-form-' + id).submit();
            }
        });
    }
</script>

@endsection
