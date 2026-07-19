@extends('layouts.app')

@section('title', 'My Housekeeping Tasks')
@extends('layouts.partials.navbar')
@section('content')
<div class="container-fluid pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Dashboard</h2>
        <div>
            <span class="badge bg-primary fs-6">{{ ucfirst($role ?? 'User') }}</span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-left-warning shadow h-100 py-4">
                <div class="card-body text-center">
                    <h1 class="display-4 fw-bold {{ $total_tasks > 0 ? 'text-danger' : 'text-success' }}">
                        {{ $total_tasks }}
                    </h1>
                    <h4 class="mt-2">Pending / In Progress Tasks</h4>
                    <p class="text-muted">
                        Completed today: <strong>{{ $completed_today }}</strong> / {{ $total_tasks + $completed_today }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if($tasks->count() > 0)
    <div class="row">
        @foreach($tasks as $task)
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Room {{ $task->room->room_number }}</h6>
                        <span class="badge bg-{{ $task->status == 'in_progress' ? 'warning' : 'info' }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <p><strong>Type:</strong> {{ $task->room->roomType->name ?? 'N/A' }}</p>
                        <p class="mb-2"><strong>Status:</strong> 
                            <span class="badge bg-{{ $task->status == 'in_progress' ? 'warning' : 'info' }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </p>
                        <p><strong>Assigned:</strong> {{ $task->created_at->format('d/m/Y H:i') }}</p>

                        @if($task->description)
                            <p><strong>Notes:</strong> {{ $task->description }}</p>
                        @endif

                        <div class="mt-4 text-center">
                            @if($task->status == 'pending')
                                <form method="POST" action="{{ route('housekeeping.tasks.start', $task->id) }}" class="d-inline" onsubmit="return confirm('You are about to start this task now. Are you sure?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning btn-lg px-5 text-dark font-weight-bold shadow-sm">
                                        <i class="fas fa-play-circle me-2"></i> Start Cleaning
                                    </button>
                                </form>
                            @elseif($task->status == 'in_progress')
                                <form method="POST" action="{{ route('housekeeping.tasks.complete', $task->id) }}" class="d-inline" onsubmit="return confirm('Are you sure the cleaning is complete?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm">
                                        <i class="fas fa-check-circle me-2"></i> Finish Cleaning
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline-danger btn-sm mt-2 border-0 fw-bold" data-bs-toggle="modal" data-bs-target="#reportIssueModal{{ $task->id }}">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Report Issue
                                </button>
                                <!-- Modal for reporting an issue -->
                                <div class="modal fade" id="reportIssueModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title fw-bold"><i class="fas fa-tools me-2"></i>Report Issue: Room {{ $task->room_number }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('housekeeping.report-issue', $task->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body text-start">
                                                    <label class="form-label fw-bold text-dark">Describe what is the problem (Lights, AC, Bed, Water supply, etc)</label>
                                                    <textarea name="issue_notes" class="form-control" rows="4" required placeholder="Mfano: AC haitoi baridi kabisa au Kioo cha bafuni kimevunjika..."></textarea>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger fw-bold shadow-sm">Send Report</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Progress Summary -->
    <div class="card shadow my-5">
        <div class="card-body text-center py-4">
            <h5 class="mb-3">Progress Today</h5>
            <div class="progress" style="height: 25px;">
                <div class="progress-bar bg-success" role="progressbar" 
                    style="width: {{ $total_tasks + $completed_today > 0 ? ($completed_today / ($total_tasks + $completed_today)) * 100 : 0 }}%;" 
                    aria-valuenow="{{ $completed_today }}" aria-valuemin="0" aria-valuemax="{{ $total_tasks + $completed_today }}">
                    {{ $completed_today }} / {{ $total_tasks + $completed_today }} Completed
                </div>
            </div>
            <p class="mt-3 text-muted">You are doing great! Keep it up.</p>
        </div>
    </div>

    @else
    <div class="text-center py-5">
        <i class="bi bi-check-circle-fill fa-5x text-success mb-4"></i>
        <h3 class="text-success">Congratulations!</h3>
        <p class="lead text-muted">No pending tasks at the moment.</p>
    </div>
    @endif

    <!-- My shift -->
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

    <!-- Recent cleaning -->
    <div class="row mt-4 pb-3">
        <div class="col-12">
                <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>Recent Cleaning</h5>
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
                    @forelse($recentTasks as $task)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-success fs-6">Room No. {{ $task->room->room_number }}</span></td>
                        <td><strong>{{ $task->room->roomType->type_name ?? 'Room' }}</strong></td>
                        <td class="font-monospace">{{ \Carbon\Carbon::parse($task->completed_at)->format('d M Y, h:i A') }}</td>
                        <td>
                            <span class="badge bg-success">Completed</span>
                        </td>
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
    </div>
    <!--Dirty Rooms -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white py-3">
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
        </div>
    </div>
</div>

@endsection