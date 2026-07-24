@extends('layouts.app')

@section('title', 'Add New Room')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-light">Add New Room</h5>
        <a href="{{ route('rooms.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                <i class="fas fa-arrow-left"></i> Back to Rooms
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data" onsubmit="triggerSaveSettings(event)">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Room Number</label>
                    <input type="text" name="room_number" class="form-control @error('room_number') is-invalid @enderror" required>
                    @error('room_number')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Room Type</label>
                    <select name="room_type_id" class="form-select @error('room_type_id') is-invalid @enderror" required>
                        <option value="">-- Select Room Type --</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }} - TSh {{ number_format($type->price_per_night) }}</option>
                        @endforeach
                    </select>
                    @error('room_type_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Initial Status</label>
                    <select name="status" class="form-select">
                        <option value="available" selected>Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="cleaning">Cleaning</option>
                        <option value="dirty">Dirty</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Floor</label>
                    <input type="text" name="floor" class="form-control @error('floor') is-invalid @enderror" required>
                    @error('floor')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Room</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Triggering Save setting form
    function triggerSaveSettings(event) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Saving room...',
                text: 'Please wait while a new room is created.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    }
</script>
@endsection