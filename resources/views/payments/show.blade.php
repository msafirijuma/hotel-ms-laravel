@extends('layouts.app')

@section('title', 'Payment Details')

@php
    $total_paid = $payment->booking->payments
        ? $payment->booking->payments->where('status', 'paid')->sum('amount_paid')
        : 0;
    $total_amount = $payment->booking->total_amount;
    $remaining_balance = $total_amount - $total_paid;
@endphp

@section('content')
<div class="container-fluid pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Payment Details</h1>
        <div>
            <a href="{{ route('payments.invoice', $payment) }}" class="btn btn-outline-dark">
                <i class="fas fa-receipt"></i> 
                <span>{{ $total_paid >= $total_amount ? 'RECEIPT' : 'INVOICE' }}</span>
            </a>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="40%">Invoice Number</th>
                            <td><strong>{{ $payment->invoice_number }}</strong></td>
                        </tr>
                        <tr>
                            <th>Amount Paid</th>
                            <td><strong>TZS {{ number_format($payment->amount_paid, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td>{{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Payment Date</th>
                            <td>{{ $payment->payment_date?->format('d M Y, H:i') ?? $payment->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Related Booking</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="40%">Booking Code</th>
                            <td>
                                <a href="{{ route('bookings.show', $payment->booking_id) }}" class="text-decoration-none">
                                    <strong class="text-dark">{{ $payment->booking->booking_code ?? 'N/A' }}</strong>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Guest</th>
                            <td>{{ $payment->booking->guest->full_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Room</th>
                            <td>{{ $payment->booking->room->room_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <td>TZS {{ number_format($payment->booking->total_amount, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection