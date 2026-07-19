@extends('layouts.app')

@section('title', 'Shift Management')

@section('content')
<div class="container-fluid">
    
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2"></i>Shift Management</h5>
        </div>
        
        <div class="card-body p-4">
            
            <!-- Error handling -->
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

            <!-- Add new shift -->
            <div class="card border shadow-none mb-4 bg-light">
                <div class="card-header bg-secondary text-white fw-bold py-2">Add New Shift</div>
                <div class="card-body">
                    <form action="{{ route('shifts.store') }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted">Shift Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="Morning Shift" value="{{ old('name') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted">Start Time</label>
                                <input type="time" name="start_time" class="form-control" required value="{{ old('start_time') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted">End Time</label>
                                <input type="time" name="end_time" class="form-control" required value="{{ old('end_time') }}">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="submit" class="btn btn-primary w-100 fw-bold" title="Add New Shift">
                                    <i class="fas fa-plus-circle me-1"></i> Add
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Available shifts -->
            <div class="card border shadow-none">
                <div class="card-header bg-dark text-white fw-bold py-2 mb-2">Available Shifts</div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle mb-0" id="shiftTable" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Shift Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shifts as $shift)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong class="text-dark">{{ $shift->name }}</strong></td>
                                    <td class="text-primary font-monospace">{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</td>
                                    <td class="text-danger font-monospace">{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center align-items-center">
                                            <!-- Edit shift -->
                                            <a href="{{ route('shifts.edit', $shift->id) }}" class="btn btn-sm btn-warning text-dark py-1" title="Edit Shift">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <!-- Delete shift -->
                                            <form action="{{ route('shifts.destroy', $shift->id) }}" method="POST" class="m-0 border-0 p-0" onsubmit="return confirm('This action can\'t be undone. Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger py-1" title="Delete Shift">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted p-4">No shift yet. Add new shift at the top.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
