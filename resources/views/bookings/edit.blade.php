@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Booking: {{ $booking->booking_code }}</h5>
            <a href="{{ route('bookings.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                <i class="fas fa-arrow-left"></i> Back to Bookings
            </a>
        </div>
        
        <div class="card-body">
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

            <form action="{{ route('bookings.update', $booking->id) }}" method="POST" enctype="multipart/form-data" onsubmit="triggerSaveSettings(event)">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Left Side: Dates & Room Info -->
                    <div class="col-lg-6">
                        <h5 class="mb-3 text-secondary border-bottom pb-2">Stay Details</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Check-in</label>
                                <input type="date" class="form-control bg-light" value="{{ $booking->check_in_date }}" readonly>
                                <input type="hidden" id="check_in_date" value="{{ $booking->check_in_date }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-primary">Check-out <span class="text-danger">*</span></label>
                                <input type="date" name="check_out_date" id="check_out_date" class="form-control border-primary" 
                                       value="{{ old('check_out_date', $booking->check_out_date) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted">Assigned Room</label>
                            <input type="text" class="form-control bg-light" 
                                   value="No. {{ $booking->room->room_number }} - {{ $booking->room->roomType->type_name ?? 'N/A' }}" readonly>
                            <!-- save room price for whenever changes (JS action) -->
                            <input type="hidden" id="room_price" value="{{ $booking->room->roomType->price_per_night ?? 0 }}">
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label font-weight-bold">Adults</label>
                                <input type="number" name="adults" class="form-control" min="1" value="{{ old('adults', $booking->adults) }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label font-weight-bold">Children</label>
                                <input type="number" name="children" class="form-control" min="0" value="{{ old('children', $booking->children) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Updated Total Amount (TZS)</label>
                            <input type="text" id="total_amount" class="form-control fw-bold text-success fs-5 bg-light" readonly 
                                   value="{{ number_format($booking->total_amount) }} TZS">
                        </div>
                    </div>

                    <!-- Right Side: Guest Info (Readonly for consistency) -->
                    <div class="col-lg-6">
                        <h5 class="mb-3 text-secondary border-bottom pb-2">Guest Profile (View Only)</h5>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted font-weight-bold">Full Name</label>
                            <input type="text" class="form-control bg-light" value="{{ $booking->guest->full_name ?? 'N/A' }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted font-weight-bold">Phone Number</label>
                            <input type="text" class="form-control bg-light" value="{{ $booking->guest->phone ?? 'N/A' }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted font-weight-bold">Email</label>
                            <input type="text" class="form-control bg-light" value="{{ $booking->guest->email ?? '—' }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Additional Notes</label>
                            <textarea name="notes" class="form-control" rows="4">{{ old('notes', $booking->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-warning btn-lg px-5 text-dark font-weight-bold">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');
    const priceInput = document.getElementById('room_price');
    const totalField = document.getElementById('total_amount');

    function reCalculateTotal() {
        if (!checkInInput || !checkOutInput || !priceInput || !totalField) return;

        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        const price = parseFloat(priceInput.value) || 0;

        if (checkIn && checkOut && checkOut >= checkIn) {
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            const total = (nights === 0 ? 1 : nights) * price;
            totalField.value = total.toLocaleString() + ' TZS';
        } else {
            totalField.value = '0 TZS';
        }
    }

    // Run functions when changes happen
    if (checkOutInput) {
        checkOutInput.addEventListener('change', reCalculateTotal);
    }
});
</script>

<script>
    // Triggerign Save setting form
    function triggerSaveSettings(event) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Saving changes...',
                text: 'Please wait while a booking is being updated.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    }
</script>
@endsection
