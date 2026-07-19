@extends('layouts.app')

@section('title', 'Housekeeping Tasks')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-tasks me-2"></i>Housekeeping Tasks Management</h5>
            <a href="{{ route('housekeeping.assign') }}" class="btn btn-light btn-sm text-primary fw-bold">
                <i class="fas fa-plus-circle me-1"></i> Assign New Task
            </a>
        </div>
        
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle mb-0" id="housekeepingTable" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Room Info</th>
                            <th>Assigned Housekeeper</th>
                            <th>Status</th>
                            <th>Assigned By</th>
                            <th>Assigned At</th>
                            <th class="text-center">Quick Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-secondary fs-6">No. {{ $task->room->room_number }}</span>
                                <small class="text-muted d-block mt-1">{{ $task->room->roomType->name ?? 'Room' }}</small>
                            </td>
                            <td>
                                <strong class="text-dark">{{ $task->housekeeper->name ?? 'N/A' }}</strong>
                                <small class="text-muted d-block" style="font-size: 12px;">{{ $task->shift_name ?? 'No Schedule' }}</small>
                            </td>
                            <td>
                                <!-- Status Badges -->
                                <span class="badge px-3 py-1.5 text-uppercase bg-{{ 
                                    $task->status == 'completed' ? 'success' : 
                                    ($task->status == 'in_progress' ? 'info text-white' : 'warning text-dark') 
                                }}">
                                    {{ str_replace('_', ' ', $task->status) }}
                                </span>
                            </td>
                            <td>
                            <strong class="text-dark">{{ $task->creator->name ?? 'System' }}</strong>
                            <small class="text-muted d-block" style="font-size: 12px;">{{ $task->creator ? (ucfirst($task->creator->getRoleNames()->first()) ?? 'User') : '—' }}</small>
                            </td>
                            <td class="small text-muted font-monospace">
                                {{ \Carbon\Carbon::parse($task->assigned_at)->format('d M, h:i A') }}
                            </td>
                            <td class="text-center">
                                <!-- Quick actions -> changing task status -->
                                
                                @if($task->status == 'pending')
                                    <form action="{{ route('housekeeping.tasks.start', $task->id) }}" method="POST" class="m-0 p-0 d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-info text-white fw-bold px-3">
                                            <i class="fas fa-play me-1"></i> Start Work
                                        </button>
                                    </form>
                                @elseif($task->status == 'in_progress')
                                    <form action="{{ route('housekeeping.tasks.complete', $task->id) }}" method="POST" class="m-0 p-0 d-inline" onsubmit="return confirm('Je, una uhakika usafi umekamilika na chumba kipo safi sasa hivi?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success fw-bold px-3">
                                            <i class="fas fa-check-circle me-1"></i> Mark Done
                                        </button>
                                    </form>
                                @else
                                    <!-- if task is done, display time of completion -->
                                    <span class="text-success small fw-bold">
                                        <i class="fas fa-check-double"></i> Cleaned at {{ \Carbon\Carbon::parse($task->completed_at)->format('d M, h:i A') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
