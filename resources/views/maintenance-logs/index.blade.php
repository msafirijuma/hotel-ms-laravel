@extends('layouts.app')

@section('title', 'Maintenance Logs')

@section('title', 'Room Maintenance Logs')

@section('content')
<div class="container-fluid pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-tools"></i> Room Maintenance Logs
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle" id="maintenanceTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Room No.</th>
                            <th>Issue Category</th>
                            <th>Issue Description</th>
                            <th>Reported By</th>
                            <th>Reported At</th>
                            <th>Status</th>
                            @if(auth()->user()->hasRole('admin'))
                                <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-danger fs-6">Room {{ $log->room->room_number }}</span>
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $log->category ?? 'General' }}</span>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 300px;">
                                    {{ Str::limit($log->issue_description, 80) }}
                                </div>
                            </td>
                            <td>{{ $log->reportedBy->name ?? 'System' }}</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $log->status == 'fixed' ? 'success' : 'danger' }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>

                            @if(auth()->user()->hasRole('admin'))
                                <td class="text-center">
                                    @if($log->status != 'fixed')
                                        <form action="{{ route('maintenance.fixed', $log) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Mark this issue as fixed?')">
                                                <i class="bi bi-check-circle"></i> Mark Fixed
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-success">Fixed</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                        @empty
                        {{-- <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-check-circle fa-4x text-success mb-3"></i>
                                <h5>No maintenance issues at the moment.</h5>
                                <p class="small">All rooms are in good condition.</p>
                            </td>
                        </tr> --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Global simple loading 
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

    $(document).ready(function() {
        $('.mark-fixed-form').on('submit', function(e) {
            e.preventDefault(); 
            
            var form = this;
            var roomNumber = $(this).data('room'); // Capture dynamic room number parameter

            // Confirmation box
            Swal.fire({
                title: 'Is the issue resolved?',
                text: `Confirm that technicians have fixed the issue for Room No. ${roomNumber}!`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754', 
                cancelButtonColor: '#6c757d',  
                confirmButtonText: '<i class="fas fa-check"></i> Yes, Issue Fixed!',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-success btn-lg px-4 me-2 fw-bold shadow-sm',
                    cancelButton: 'btn btn-secondary btn-lg px-4 fw-bold shadow-sm'
                },
                buttonsStyling: false 
            }).then((result) => {
                // loading function
                if (result.isConfirmed) {
                    showPageLoader('Resolving maintenance issue...');
                    form.submit(); 
                }
            });
        });
    });
</script>
@endsection

