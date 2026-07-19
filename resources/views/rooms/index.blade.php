@extends('layouts.app')

@section('title', 'Rooms')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
        <h5>
            <i class="fas fa-bed me-2"></i>Rooms Management
        </h5>
        <a href="{{ route('rooms.create') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                <i class="fas fa-plus"></i> Add New Room
            </a>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered align-middle" id="roomsTable">
                <thead class="table-dark">
                    <tr>
                        <th>Room No.</th>
                        <th>Floor</th>
                        <th>Type</th>
                        <th>Price/night</th>
                        <th>Status</th>
                        <th>Update Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr>
                        <td><strong>{{ $room->room_number }}</strong></td>
                        <td>
                            <span class="badge bg-secondary">{{ $room->floor ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $room->roomType->name ?? 'N/A' }}</td>
                        <td>{{ $room->roomType->price_per_night }}</td>
                        <!-- Status Badge -->
                        <td>
                            <span class="badge bg-{{ 
                                $room->status == 'available' ? 'success' : 
                                ($room->status == 'occupied' ? 'primary' : 
                                ($room->status == 'dirty' ? 'danger' : 'warning'))
                            }}">
                            
                                {{ ucfirst($room->status) }}
                            </span>
                        </td>

                        <!-- Inline Status Update -->
                        <td>
                    <form action="{{ route('rooms.update-status', $room->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        
                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                            <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="cleaning" {{ $room->status == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                            <option value="dirty" {{ $room->status == 'dirty' ? 'selected' : '' }}>Dirty</option>
                            <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </form> 
                        </td>
                        <td>
                            <a href="{{ route('rooms.show', $room) }}" class="btn btn-sm btn-info" title="View this room">
                                <i class="fas fa-eye"></i> 
                            </a>
                            <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-warning" title="Edit this room">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete this room" onclick="return confirm('Delete this room?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $rooms->links() }}
        </div>
    </div>
</div>
@endsection