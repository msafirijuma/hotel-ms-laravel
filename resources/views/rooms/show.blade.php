@extends('layouts.app')

@section('title', 'Room Details')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-door-open me-2"></i>Details: Room No. {{ $room->room_number }}
            </h5>
            <a href="{{ route('rooms.index') }}" class="btn btn-light btn-sm text-primary fw-bold">
                <i class="fas fa-arrow-left me-1"></i> Back to Rooms
            </a>
        </div>
        
        <div class="card-body p-4">
            <div class="row g-4">
                <!-- Lef side: Room Type Primary Image -->
                <div class="col-lg-5 text-center border-end-md">
                    <div class="p-2 bg-light rounded-3 mb-3 border">
                        <img src="{{ $room->roomType && $room->roomType->image ? asset('storage/' . $room->roomType->image) : asset('assets/img/no-image.png') }}" 
                             class="img-fluid rounded-3 shadow-sm" 
                             style="max-height: 280px; width: 100%; object-fit: cover;" 
                             alt="Room Image">
                    </div>
                    
                    <h4 class="fw-bold text-dark mb-1">Room No. {{ $room->room_number }}</h4>
                    <p class="text-muted fw-semibold mb-3">{{ $room->roomType->name ?? 'Unknown' }}</p>
                    
                    <!-- Room Status Badge -->
                    <div class="mb-2">
                        <span class="badge px-4 py-2 text-uppercase fs-6 shadow-sm bg-{{ 
                            $room->status == 'available' ? 'success' :
                            ($room->status == 'occupied' ? 'primary' :
                            ($room->status == 'dirty' ? 'danger' : 'warning')) 
                        }}">
                            <i class="fas fa-info-circle me-1"></i> {{ $room->status }}
                        </span>
                    </div>
                </div>

                <!-- Right side: Room Details -->
                <div class="col-lg-7 ps-lg-4">
                    <h5 class="text-secondary border-bottom pb-2 mb-4 fw-bold">
                        <i class="fas fa-list-alt me-2 text-primary"></i>Room Details
                    </h5>
                    
                    <div class="row g-4">
                        <!-- Room Type -->
                        <div class="col-md-6">
                            <span class="text-muted d-block small text-uppercase fw-bold">Room Type</span>
                            <strong class="fs-5 text-primary">{{ $room->roomType->name ?? '—' }}</strong>
                        </div>
                        
                        <!-- Price per night -->
                        <div class="col-md-6">
                            <span class="text-muted d-block small text-uppercase fw-bold">Price Per Night</span>
                            <strong class="fs-5 text-success">
                                TZS {{ number_format($room->roomType->price_per_night ?? 0, 0) }}
                            </strong>
                        </div>

                        <!-- Floor -->
                        <div class="col-md-6">
                            <span class="text-muted d-block small text-uppercase fw-bold">Floor</span>
                            <strong class="fs-5 text-dark">{{ $room->floor ?? 'Ground Floor (0)' }}</strong>
                        </div>

                        <!-- Maximum Occupancy -->
                        <div class="col-md-6">
                            <span class="text-muted d-block small text-uppercase fw-bold">Maximum Capacity</span>
                            <strong class="fs-5 text-dark">
                                <i class="fas fa-user text-muted me-1"></i> {{ $room->roomType->max_adults ?? 1 }} Adults 
                                <span class="mx-1 text-muted">|</span>
                                <i class="fas fa-child text-muted me-1"></i> {{ $room->roomType->max_children ?? 0 }} Children
                            </strong>
                        </div>

                        <!-- Additional details -->
                        <div class="col-12">
                            <span class="text-muted d-block small text-uppercase fw-bold mb-1">Additional Notes</span>
                            <div class="p-3 bg-light rounded-3 border text-secondary" style="min-height: 90px; font-size: 14px;">
                                {{ $room->notes ?? 'No additional notes provided for this room.' }}
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                        <a onclick="triggerEdit('{{ route('rooms.edit', $room->id) }}')" class="btn btn-warning text-dark fw-bold px-4">
                            <i class="fas fa-edit me-1"></i> Edit Room
                        </a>
                        
                        @if($room->status == 'available')
                            <a onclick="triggerAddBooking({{ route('bookings.create', ['room_id' => $room->id]) }})" class="btn btn-success fw-bold px-4">
                                <i class="fas fa-calendar-plus me-1"></i> Add Booking
                            </a>
                        @endif
                    </div>
                </div>
            </div>
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

    function triggerAddBooking(url) {
        showPageLoader('New booking form is being prepared...');
        window.location.href = url;
    }

    // Edit Action Loading
    function triggerEdit(url) {
        showPageLoader('We are preparing room update form...');
        window.location.href = url;
    }
    </script>
@endsection
