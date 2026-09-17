@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')
<div class="container-fluid pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Record Payment</h1>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Select Booking</label>
                        <select name="booking_id" class="form-select @error('booking_id') is-invalid @enderror" required>
                            <option value="">-- Select Booking --</option>
                            @foreach($bookings as $b)
                                <option value="{{ $b->id }}"
                                    {{ (isset($booking) && $booking->id == $b->id) || old('booking_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->booking_code ?? 'BK-'.$b->id }} — {{ $b->guest->full_name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('booking_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Amount Paid (TZS)</label>
                        <input type="number" step="0.01" name="amount_paid" class="form-control @error('amount_paid') is-invalid @enderror"
                               value="{{ old('amount_paid') }}" min="1" required>
                        @error('amount_paid') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="cash">Cash</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="tigo_pesa">Tigo Pesa</option>
                            <option value="airtel_money">Airtel Money</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="card">Card</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="datetime-local" name="payment_date" class="form-control"
                               value="{{ old('payment_date', now()->format('Y-m-d\TH:i')) }}">
                    </div>
                </div>

                @if(isset($booking))
                    @php
                        $alreadyPaid = $booking->payments->where('status', 'paid')->sum('amount_paid');
                        $balance = $booking->total_amount - $alreadyPaid;
                    @endphp
                    <div class="alert alert-info">
                        <strong>Booking Summary</strong><br>
                        Guest: {{ $booking->guest->full_name ?? 'N/A' }} <br>
                        Total Amount: <strong>TZS {{ number_format($booking->total_amount, 2) }}</strong> <br>
                        Already Paid: TZS {{ number_format($alreadyPaid, 2) }} <br>
                        <strong>Remaining Balance: TZS {{ number_format($balance, 2) }}</strong>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">Save Payment</button>
                <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection