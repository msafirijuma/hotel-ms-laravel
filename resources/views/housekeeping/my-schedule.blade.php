@extends('layouts.app')
@section('title', 'My Work Schedule')
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2"></i>My Shift Schedule</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle" id="housekeepingTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Assigned Shift</th>
                        <th>Shift Hours</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $sched)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold font-monospace">{{ \Carbon\Carbon::parse($sched->shift_date)->format('l, d M Y') }}</td>
                        <td><span class="badge bg-info text-dark px-3 py-1.5 fw-bold">{{ $sched->shift->name ?? 'N/A' }}</span></td>
                        <td class="font-monospace text-secondary">
                            {{ \Carbon\Carbon::parse($sched->shift->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($sched->shift->end_time)->format('h:i A') }}
                        </td>
                        <td class="text-muted"><small>{{ $sched->notes ?? '-' }}</small></td>
                    </tr>
                    @empty
                    {{-- <tr>
                        <td colspan="5" class="text-center text-muted py-4">Not assigned for any shift yet. Please stay patient.</td>
                    </tr> --}}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
