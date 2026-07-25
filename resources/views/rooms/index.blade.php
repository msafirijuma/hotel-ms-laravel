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
                        <th>Type</th>
                        <th>Floor</th>
                        <th>Price/night</th>
                        <th>Status</th>
                        <th>Update Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                    <tr>
                        <td><span class="badge bg-secondary fs-6">No. {{ $room->room_number }}</span></td>
                        <td><strong>{{ $room->roomType->name ?? 'Room' }}</strong></td>
                        <td>{{ $room->floor ?? '-' }}</td>
                        <td>{{ $room->roomType->price_per_night ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{
                                    $room->status == 'available' ? 'success' : 
                                    ($room->status == 'occupied' ? 'primary' : 
                                    ($room->status == 'dirty' ? 'danger' : 
                                    ($room->status == 'cleaning' ? 'info' : 'warning')))
                                }} text-uppercase">
                                {{ $room->status }}
                            </span>
                        </td>

                        <!-- Inline Status Update -->
                        <td>
                            <form action="{{ route('rooms.update-status', $room) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                
                                <select name="status" class="form-select form-select-sm d-inline w-auto" 
                                        onchange="this.form.submit()">
                                    
                                    @if(auth()->user()->hasRole('admin'))
                                        <!-- Admin  -->
                                        <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                        <option value="dirty" {{ $room->status == 'dirty' ? 'selected' : '' }}>Dirty</option>
                                        <option value="cleaning" {{ $room->status == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                        <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    @else
                                        <!-- Receptionist -->
                                        @if($room->status == 'maintenance')
                                            <option value="maintenance" selected>Maintenance</option>
                                        @else
                                            <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                            <option value="cleaning" {{ $room->status == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                            <option value="dirty" {{ $room->status == 'dirty' ? 'selected' : '' }}>Dirty</option>
                                        @endif
                                    @endif
                                </select>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center align-items-center">
                                
                                <!-- VIEW BUTTON-->
                                <button type="button" onclick="triggerView('{{ route('rooms.show', $room->id) }}')" class="btn btn-sm btn-info text-white py-1" title="View Room Details">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- EDIT BUTTON-->
                                <button type="button" onclick="triggerEdit('{{ route('rooms.edit', $room->id) }}')" class="btn btn-sm btn-warning py-1" title="Edit Room Info">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Laravel Delete Form Component-->
                                <form id="delete-room-form-{{ $room->id }}" action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <!-- DELETE BUTTON-->
                                <button type="button" onclick="triggerDelete({{ $room->id }}, '{{ $room->room_number }}')" class="btn btn-sm btn-danger py-1" title="Delete Room">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-door-closed fa-3x d-block mb-2 text-secondary"></i>
                            No room information is currently available in the system.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $rooms->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Loader function during page navigation
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

    // View Action Loading
    function triggerView(url) {
        showPageLoader('We are opening the profile and room details...');
        window.location.href = url;
    }

    // Edit Action Loading
    function triggerEdit(url) {
        showPageLoader('We are preparing room update form...');
        window.location.href = url;
    }

    // SweetAlert Delete Action
    function triggerDelete(id, roomNumber) {
        Swal.fire({
            title: 'Are you sure you want to delete?',
            text: `You will permanently remove "Room No. ${roomNumber}" from the hotel system!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545', 
            cancelButtonColor: '#6c757d',  
            confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete!',
            cancelButtonText: 'No, Cancel',
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-danger btn-lg px-4 me-2 fw-bold shadow-sm',
                cancelButton: 'btn btn-secondary btn-lg px-4 fw-bold shadow-sm'
            },
            buttonsStyling: false 
        }).then((result) => {
            if (result.isConfirmed) {
                // Loader during the deletion process
                Swal.fire({
                    title: '<span class="text-danger"><i class="fas fa-trash me-2"></i>Deleting...</span>',
                    text: 'Please wait while the room is being deleted from the database.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // submit form of selected room
                document.getElementById('delete-room-form-' + id).submit();
            }
        });
    }
</script>
@endsection
