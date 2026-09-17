@extends('layouts.app')

@php
    $total_paid = $payment->booking->payments
        ? $payment->booking->payments->where('status', 'paid')->sum('amount_paid')
        : 0;
    $total_amount = $payment->booking->total_amount;
    $remaining_balance = $total_amount - $total_paid;
@endphp

@section('title', $total_paid >= $total_amount ? 'Payment Receipt' : 'Invoice')

@section('content')
<div class="container max-w-3xl">
    <div class="d-flex justify-content-end mb-3 no-print">
        <button onclick="window.print();" class="btn btn-outline-dark me-2">
            <i class="fas fa-print"></i> Print
        </button>
        <a href="{{ route('payments.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i> Back to Payments
        </a>
    </div>

    <div class="card shadow-sm p-4 bg-white border">
        <div class="row mb-4">
            <div class="col-6">
                <h3 class="text-primary fw-bold mb-0">{{ $settings->hotel_name ?? 'Hotel MS' }}</h3>
                <small class="text-muted d-block fw-bold">{{ $settings->address ?? 'Dar es Salaam, Tanzania' }}</small>
            </div>
            <div class="col-6 text-end">
                <h4 class="text-uppercase text-primary fw-bold mb-1">
                    {{ $total_paid >= $total_amount ? 'RECEIPT' : 'INVOICE' }}
                </h4>
                <strong>Number:</strong> {{ $payment->invoice_number }}<br>
                <strong>Date:</strong> {{ $payment->payment_date?->format('d/m/Y H:i') ?? $payment->created_at->format('d/m/Y H:i') }}
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-6">
                <h6 class="text-muted mb-1 text-uppercase small fw-bold">Customer Information</h6>
                <strong>Name:</strong> {{ $payment->booking->guest->full_name ?? 'Guest' }}<br>
                <strong>Email:</strong> {{ $payment->booking->guest->email ?? '—' }}<br>
                <strong>Phone:</strong> {{ $payment->booking->guest->phone ?? '—' }}
            </div>
            <div class="col-6 text-end">
                <h6 class="text-muted mb-1 text-uppercase small fw-bold">Payment Details</h6>
                <strong>Method:</strong> <span class="text-uppercase">{{ str_replace('_', ' ', $payment->payment_method) }}</span><br>
                <strong>Status:</strong>
                @if($total_paid >= $total_amount)
                    <span class="badge bg-success">Fully Paid</span>
                @elseif($total_paid > 0)
                    <span class="badge bg-warning text-dark">Partially Paid</span>
                @else
                    <span class="badge bg-danger">Unpaid</span>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th>Service Information</th>
                        <th class="text-center">Room</th>
                        <th class="text-end">This Transaction (TZS)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Room Type: <strong>{{ $payment->booking->room->roomType->name ?? 'Room' }}</strong><br>
                            <small class="text-muted">
                                From: {{ \Carbon\Carbon::parse($payment->booking->check_in_date)->format('d/m/Y') }}
                                to {{ \Carbon\Carbon::parse($payment->booking->check_out_date)->format('d/m/Y') }}
                            </small>
                        </td>
                        <td class="text-center">No. {{ $payment->booking->room->room_number ?? '—' }}</td>
                        <td class="text-end font-monospace">{{ number_format($payment->amount_paid, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end text-muted small">TOTAL PAID:</th>
                        <th class="text-end text-success font-monospace">TZS {{ number_format($total_paid, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-end text-muted small">TOTAL ROOM BILL:</th>
                        <th class="text-end text-primary font-monospace">TZS {{ number_format($total_amount, 2) }}</th>
                    </tr>
                    @if($remaining_balance > 0)
                    <tr>
                        <th colspan="2" class="text-end text-muted small">BALANCE DUE:</th>
                        <th class="text-end text-danger font-monospace">TZS {{ number_format($remaining_balance, 2) }}</th>
                    </tr>
                    @endif
                </tfoot>
            </table>
        </div>

        <div class="text-center mt-3">
            <p class="mb-0 text-muted">
                "{{ $settings->footer_message ?? 'Thanks for choosing our hotel, Welcome again!' }}"
            </p>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print, .app-sidebar-container, .navbar, footer, .mobile-topbar {
            display: none !important;
        }
        .app-content-container, .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .container {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
    }
</style>
@endsection