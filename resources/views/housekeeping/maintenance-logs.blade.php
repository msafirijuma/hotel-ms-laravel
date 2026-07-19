@extends('layouts.app')

@section('title', 'Maintenance Logs')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-tools me-2"></i>Hotel Maintenance Logs (Rooms under maintenance)</h5>
        </div>
        
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle mb-0" id="roomsTable" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Room No.</th>
                            <th>Room Type</th>
                            <th>Issue Notes</th>
                            <th>Current Status</th>
                            @if(auth()->user()->hasRole('admin') || auth()->user()->role === 'admin')
                                <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maintenanceRooms as $room)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-danger fs-6">Room No. {{ $room->room_number }}</span></td>
                            <td><strong>{{ $room->roomType->name ?? 'Room' }}</strong></td>
                            <td>
                                <div class="p-2 bg-light rounded text-danger fw-semibold border-start border-danger border-3" style="font-size: 14px;">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $room->notes ?? 'Not detailed.' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-danger text-uppercase px-3 py-1.5 animate-pulse">
                                    <i class="fas fa-hammer me-1"></i> Under Repair
                                </span>
                            </td>
                            
                            <!-- Security: Mark fixed is for Admin only -->
                            @if(auth()->user()->hasRole('admin') || auth()->user()->role === 'admin')
                                <td class="text-center">
                                    <form action="{{ route('maintenance.fixed', $room->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Have the technicians already located and fixed the issue with this room?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success fw-bold px-3 shadow-sm">
                                            <i class="fas fa-check me-1"></i> Mark Fixed
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                <h5>Congratulations! There are currently no issues in any of the rooms.</h5>
                                <p class="small text-muted mb-0">All rooms are safe and ready for commercial use.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
