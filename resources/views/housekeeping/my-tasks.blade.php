@extends('layouts.app')

@section('title', 'My Tasks')

@section('content')
<div class="container-fluid pt-4">

    <div class="card">
        <!-- Header -->
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-bell"></i> My Tasks
            </h1>
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm text-primary fw-bold">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>

        <!-- Tasks Cards -->
        @if($tasks->count() > 0)
            <div class="row p-2">
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
                                        <form method="POST" action="{{ route('housekeeping.tasks.start', $task->id) }}" class="d-inline start-task-form" data-room="{{ $task->room->room_number }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-warning btn-lg px-5 text-dark font-weight-bold shadow-sm">
                                                <i class="fas fa-play-circle me-2"></i> Start Cleaning
                                            </button>
                                        </form>
                                    @elseif($task->status == 'in_progress')
                                        <form method="POST" action="{{ route('housekeeping.tasks.complete', $task->id) }}" class="d-inline finish-task-form" data-room="{{ $task->room->room_number }}">
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
                                                    <form action="{{ route('logs.report-issue', $task->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body text-start">
                                                            <label class="form-label fw-bold text-dark">Describe what is the problem (Lights, AC, Bed, Water supply, etc)</label>
                                                            <textarea name="issue_description" class="form-control" rows="4" required placeholder="Mfano: AC haitoi baridi kabisa au Kioo cha bafuni kimevunjika..."></textarea>
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
         @else
            <p class="text-muted text-center py-4">No recent bookings yet.</p>
        @endif
    </div>
</div>
@endsection