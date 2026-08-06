@extends('layouts.app')

@section('title', 'Edit Room')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">
                <i class="fas fa-door-open me-2"></i>Edit Room: {{ $room->room_number }}
            </h5>
            <a href="{{ route('rooms.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                <i class="fas fa-arrow-circle-left me-2"></i> Back to Rooms
            </a>
        </div>
        
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data" onsubmit="triggerSaveRoom(event)">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-6">
                        <!-- Room Number -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Room Number <span class="text-danger">*</span></label>
                            <input type="text" name="room_number" class="form-control text-uppercase" 
                                   value="{{ old('room_number', $room->room_number) }}" required>
                        </div>

                        <!-- Room Type Selection -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Room Type <span class="text-danger">*</span></label>
                            <select name="room_type_id" id="room_type_id" class="form-select" required onchange="previewImage()">
                                <option value="">-- Choose Type --</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}" 
                                            data-image="{{ $type->image ? asset('storage/' . $type->image) : asset('assets/img/no-image.png') }}"
                                            {{ $type->id == old('room_type_id', $room->room_type_id) ? 'selected' : '' }}>
                                        {{ $type->name }} (TZS {{ number_format($type->price_per_night, 0) }}/night)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Floor -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Floor</label>
                            <input type="text" name="floor" class="form-control" 
                                   value="{{ old('floor', $room->floor) }}">
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select required">
                                    
                                    @if(auth()->user()->hasRole('admin'))
                                        <!-- Admin anaona options zote -->
                                        <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                        <option value="cleaning" {{ old('status', $room->status) == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                        <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    @else
                                        <!-- Receptionist / Housekeeper -->
                                        @if($room->status == 'maintenance')
                                            <option value="maintenance" selected>Maintenance</option>
                                        @else
                                            <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                            <option value="cleaning" {{ old('status', $room->status) == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                        @endif
                                    @endif
                                </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- Preview Room Type Image -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Preview Room Type</label>
                            <div class="border rounded p-3 bg-light text-center" style="min-height: 220px;">
                                <img id="preview_img" 
                                     src="{{ $room->roomType && $room->roomType->image ? asset('storage/' . $room->roomType->image) : asset('assets/img/no-image.png') }}" 
                                     class="img-fluid rounded shadow-sm" 
                                     style="max-height: 200px; object-fit: cover;">
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Additional Notes</label>
                            <textarea name="notes" class="form-control" rows="5">{{ old('notes', $room->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-warning btn-lg px-5 text-dark font-weight-bold">
                        <i class="fas fa-arrow-up"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage() {
        const select = document.getElementById('room_type_id');
        const selectedOption = select.options[select.selectedIndex];
        const previewImg = document.getElementById('preview_img');
        
        if (selectedOption && selectedOption.getAttribute('data-image')) {
            previewImg.src = selectedOption.getAttribute('data-image');
        } else {
            previewImg.src = "{{ asset('assets/img/no-image.png') }}";
        }
    }
</script>

@section('scripts')
<script>
    // Triggering Save setting form
    function triggerSaveRoom(event) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Updating room...',
                text: 'Please wait while a room is being updated.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    }
</script>
@endsection
