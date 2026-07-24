@extends('layouts.app')

@php
    // Total payment in this booking
    $total_paid = $payment->booking->payments ? $payment->booking->payments->sum('amount_paid') : 0;
    $total_amount = $payment->booking->total_amount;
    $remaining_balance = $total_amount - $total_paid;
@endphp

@section('title', $total_paid >= $total_amount ? 'Payment Receipt' : 'Invoice')

@section('content')
<div class="container max-w-3xl">
    <!-- Print & Back Actions Menu -->
    <div class="d-flex justify-content-end mb-3 no-print">
        <button onclick="window.print();" class="btn btn-outline-dark me-2">
            <i class="fas fa-print"></i> Print
        </button>
        <a href="{{ route('payments.index') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left me-2 "></i>Back to Payments
        </a>
    </div>

    <!-- Receipt / Invoice Card Canvas -->
    <div class="card shadow-sm p-4 bg-white border">
        <div class="row mb-4">
            <div class="col-6">
                <!-- Hotel Name -->
                <h3 class="text-primary font-weight-bold mb-0">{{ $settings->hotel_name ?? 'Hotel' }}</h3>
                
                <!-- Address -->
                <small class="text-muted d-block">{{ $settings->address ?? 'Dar es Salaam, Tanzania' }}</small>
            </div>
            <div class="col-6 text-end">
                <!-- Receipt / Invoice -->
                <h4 class="text-uppercase text-primary font-weight-bold mb-1">
                    {{ $total_paid >= $total_amount ? 'RECEIPT' : 'INVOICE' }}
                </h4>
                <strong>Number:</strong> {{ $payment->invoice_number }}<br>
                <strong>Date:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-6">
                <h6 class="text-muted mb-1" style="font-size: 12px; font-weight: bold; text-transform: uppercase;">Customer Information:</h6>
                <strong>Name:</strong> {{ $payment->booking->guest->full_name ?? 'Mgeni' }}<br>
                <strong>Email:</strong> {{ $payment->booking->guest->email ?? '—' }}
            </div>
            <div class="col-6 text-end">
                <h6 class="text-muted mb-1" style="font-size: 12px; font-weight: bold; text-transform: uppercase;">Payment Details:</h6>
                <strong>Method Used:</strong> <span class="text-uppercase">{{ str_replace('_', ' ', $payment->payment_method) }}</span><br>
                
                <!-- Payment Status Badge -->
                <strong>Payment Status:</strong> 
                @if($total_paid >= $total_amount)
                    <span class="badge bg-success px-2 py-1.5"><i class="fas fa-check-circle"></i> Fully Paid</span>
                @elseif($total_paid > 0 && $total_paid < $total_amount)
                    <span class="badge bg-warning text-dark px-2 py-1.5"><i class="fas fa-clock"></i> Partially Paid</span>
                @else
                    <span class="badge bg-danger px-2 py-1.5"><i class="fas fa-exclamation-triangle"></i> Unpaid</span>
                @endif
            </div>
        </div>

        <!-- Payment Table (Services offered) -->
        <div class="table-responsive">
            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th>Service Information</th>
                        <th class="text-center">Room</th>
                        <th class="text-end">Amount Paid - This Transaction (TZS)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Hotel Rooms of Type <strong>{{ $payment->booking->room->roomType->type_name ?? 'Room' }}</strong><br>
                            <small class="text-muted">From: {{ \Carbon\Carbon::parse($payment->booking->check_in_date)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($payment->booking->check_out_date)->format('d/m/Y') }}</small>
                        </td>
                        <td class="text-center">No. {{ $payment->booking->room->room_number }}</td>
                        <!-- Amount paid in this transaction -->
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

        <!-- Footer custom message -->
        <div class="text-center mt-5">
            <p class="mb-0 text-muted italic">
                "{{ $settings->footer_message ?? 'Thanks for choosing our hotel, Welcome again!' }}"
            </p>
        </div>
    </div>
</div>

<style>
    /* CSS print */
    @media print {
        .no-print, .app-sidebar-container, .layouts-partials-header, .navbar, head {
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
