@extends('layouts.app')
@section('title', 'Cleaning History')
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>Cleaning History</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle" id="housekeepingTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Cleaned At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-success fs-6">Room No. {{ $task->room->room_number }}</span></td>
                        <td><strong>{{ $task->room->roomType->type_name ?? 'Room' }}</strong></td>
                        <td class="font-monospace">{{ \Carbon\Carbon::parse($task->completed_at)->format('d M Y, h:i A') }}</td>
                        <td><span class="badge bg-success">COMPLETED</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">You did not accomplish any task yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
