@extends('layouts.app')

@section('title', 'Accept Payment')

@section('content')
<div class="container max-w-2xl">
    <div class="card shadow-sm">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center py-3 text-white">
            <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Hotel Payment Processing</h5>
            <a href="{{ route('bookings.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                <i class="fas fa-arrow-left"></i> Back to Bookings
            </a>
        </div>
        <div class="card-body">
            <!-- Booking Details -->
            <div class="bg-light p-3 rounded mb-4">
                <div class="row">
                    <div class="col-6 mb-2"><strong>Guest:</strong> {{ $booking->guest->full_name ?? 'Beloved Guest' }}</div>
                    <div class="col-6 mb-2"><strong>Room:</strong> No. {{ $booking->room->room_number }} ({{ $booking->room->roomType->name }})</div>
                    <div class="col-6"><strong>Total Amount:</strong> TZS {{ number_format($booking->total_amount, 2) }}</div>
                    <div class="col-6 text-danger"><strong>Remaining Amount:</strong> TZS {{ number_format($remaining_amount, 2) }}</div>
                </div>
            </div>

            <!-- Payment Form -->
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Amount Paid (TZS)</label>
                    <input type="number" name="amount_paid" class="form-control" max="{{ $remaining_amount }}" value="{{ $remaining_amount }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label font-weight-bold">Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="cash">Cash</option>
                        <option value="mpesa">Vodacom M-Pesa</option>
                        <option value="tigo_pesa">Tigo Pesa</option>
                        <option value="airtel_money">Airtel Money</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="card">Card (Visa/Mastercard)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success py-2">
                    <i class="fas fa-check-circle me-1"></i> Accept Payment & Generate Invoice
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
