@extends('layouts.app')
@section('title', 'Dirty Rooms')
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Dirty Rooms Alert</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle" id="employeeTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Current Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-danger fs-6">Room No. {{ $room->room_number }}</span></td>
                        <td><strong>{{ $room->roomType->type_name ?? 'Room' }}</strong></td>
                        <td><span class="badge bg-danger text-uppercase">Needs Cleaning</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Congratulation. No dirty room at the moment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-12">
        
    </div>
</div>
@endsection
