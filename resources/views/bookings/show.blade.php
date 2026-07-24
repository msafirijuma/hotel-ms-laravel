@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">
                <i class="fas fa-calendar-check me-2"></i>Booking Details: {{ $booking->booking_code ?? $booking->id }}
            </h5>
            <a href="{{ route('bookings.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                <i class="fas fa-arrow-left"></i> Back to Bookings
            </a>
        </div>
        
        <div class="card-body">
            <div class="row g-4">
                <!-- Accomodation Info -->
                <div class="col-md-6">
                    <h5 class="text-secondary border-bottom pb-2 mb-3">Accommodation Info</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted fw-bold" style="width: 40%;">Booking Code:</td>
                            <td><strong>{{ $booking->booking_code ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Room Number:</td>
                            <td><span class="badge bg-secondary fs-6">No. {{ $booking->room->room_number ?? 'N/A' }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Room Type:</td>
                            <td>{{ $booking->room->roomType->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Check-in Date:</td>
                            <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Check-out Date:</td>
                            <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Status:</td>
                            <td>
                                <span class="badge text-uppercase bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'secondary') }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Guest & Financial Info -->
                <div class="col-md-6">
                    <h5 class="text-secondary border-bottom pb-2 mb-3">Guest & Financial Info</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted fw-bold" style="width: 40%;">Guest Name:</td>
                            <td><strong>{{ $booking->guest->full_name ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Phone Number:</td>
                            <td>{{ $booking->guest->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Email:</td>
                            <td>{{ $booking->guest->email ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Total Bill:</td>
                            <td class="text-primary fw-bold fs-5">TZS {{ number_format($booking->total_amount) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold">Amount Paid:</td>
                            <td class="text-success fw-bold">
                                TZS {{ number_format($booking->payments ? $booking->payments->sum('amount_paid') : 0) }}
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Additional notes -->
                <div class="col-12 mt-3">
                    <h5 class="text-secondary border-bottom pb-2 mb-2">Additional Notes</h5>
                    <div class="p-3 bg-light rounded border text-muted" style="min-height: 80px;">
                        {{ $booking->notes ?? 'No additional notes provided for this booking.' }}
                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-warning text-dark font-weight-bold px-4">
                    <i class="fas fa-edit"></i> Edit Booking
                </a>
                
                @php
                    $total_paid = $booking->payments ? $booking->payments->sum('amount_paid') : 0;
                @endphp
                
                @if($total_paid < $booking->total_amount)
                    <a href="{{ route('payments.create', $booking->id) }}" class="btn btn-success px-4">
                        <i class="fas fa-money-bill-wave"></i> Collect Payment
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


