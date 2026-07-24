@extends('layouts.app')

@section('title', 'Add New Booking')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Add New Booking</h5>
            <a href="{{ route('bookings.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                <i class="fas fa-arrow-left"></i> Back to Bookings
            </a>
        </div>
        
        <div class="card-body">
            <!-- Error validation -->
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

            <form action="{{ route('bookings.store') }}" method="POST" class="booking-form" onsubmit="triggerSaveSettings(event)">
                @csrf

                <div class="row">
                    <!-- Left Side: Dates and Room Selection -->
                    <div class="col-lg-6">
                        <h5 class="mb-3 text-secondary border-bottom pb-2">Check-in and Check-out Dates</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Check-in</label>
                                <input type="date" name="check_in_date" class="form-control" 
                                       value="{{ old('check_in_date', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Check-out</label>
                                <input type="date" name="check_out_date" class="form-control" 
                                       value="{{ old('check_out_date', date('Y-m-d', strtotime('+1 day'))) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Room</label>
                            <select name="room_id" class="form-select" required onchange="calculateTotal()">
                                <option value="">-- Select Room --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" 
                                            data-price="{{ $room->roomType->price_per_night ?? 0 }}"
                                            {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        No. {{ $room->room_number }} - {{ $room->roomType->name ?? 'N/A' }} 
                                        (TZS {{ number_format($room->roomType->price_per_night ?? 0, 0) }}/night)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label font-weight-bold">Adults</label>
                                <input type="number" name="adults" class="form-control" min="1" value="{{ old('adults', 1) }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label font-weight-bold">Children</label>
                                <input type="number" name="children" class="form-control" min="0" value="{{ old('children', 0) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Total Amount (TZS)</label>
                            <input type="text" id="total_amount" class="form-control fw-bold text-primary fs-5 bg-white" readonly value="0 TZS">
                        </div>
                    </div>

                    <!-- Right Side: Guest Information -->
                    <div class="col-lg-6">
                        <h5 class="mb-3 text-secondary border-bottom pb-2">Guest Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="guest_name" class="form-control" value="{{ old('guest_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="guest_phone" class="form-control" value="{{ old('guest_phone') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">ID Number <span class="text-danger">*</span></label>
                            <input type="text" name="guest_phone" class="form-control" value="{{ old('id_number') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Email (optional)</label>
                            <input type="email" name="guest_email" class="form-control" value="{{ old('guest_email') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Additional Notes</label>
                            <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-success btn-lg px-5 booking-btn">
                        <i class="fas fa-save"></i> Add Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function calculateTotal() {
    const roomSelect = document.querySelector('[name="room_id"]');
    const checkInInput = document.querySelector('[name="check_in_date"]');
    const checkOutInput = document.querySelector('[name="check_out_date"]');
    const totalField = document.getElementById('total_amount');

    if (roomSelect && checkInInput && checkOutInput) {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);

        if (roomSelect.value && checkIn && checkOut && checkOut > checkIn) {
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            const selectedOption = roomSelect.selectedOptions[0];
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const total = nights * price;
            totalField.value = total.toLocaleString() + ' TZS';
        } else {
            totalField.value = '0 TZS';
        }
    }
}

// Run functions when changes happen
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();

    document.querySelector('[name="check_in_date"]').addEventListener('change', calculateTotal);
    document.querySelector('[name="check_out_date"]').addEventListener('change', calculateTotal);
    document.querySelector('[name="room_id"]').addEventListener('change', calculateTotal);
});
</script>

<script>
    // Triggering Save setting form
    function triggerSaveSettings(event) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Saving changes...',
                text: 'Please wait while a booking is being created.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    }
</script>

@endsection
