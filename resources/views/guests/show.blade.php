@extends('layouts.app')

@section('title', 'Guest Details')

@section('content')
<div class="container-fluid pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Guest Details</h1>
        <div>
            <a href="{{ route('guests.edit', $guest) }}" class="btn btn-warning">
                <i class="fas fa-pencil-alt"></i> Edit
            </a>
            <a href="{{ route('guests.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Guests
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Guest Information -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i> Guest Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="40%">Full Name</th>
                            <td><strong>{{ $guest->full_name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td>{{ $guest->phone }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $guest->email ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>ID Number</th>
                            <td>{{ $guest->id_number }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $guest->address ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Country</th>
                            <td>{{ $guest->country ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Registered On</th>
                            <td>{{ $guest->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Booking Summary -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i> Booking Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h3 class="mb-0">{{ $guest->bookings->count() }}</h3>
                            <small class="text-muted">Total Bookings</small>
                        </div>
                        <div class="col-4">
                            <h3 class="mb-0 text-success">
                                {{ $guest->bookings->where('status', 'checked_out')->count() }}
                            </h3>
                            <small class="text-muted">Completed</small>
                        </div>
                        <div class="col-4">
                            <h3 class="mb-0 text-warning">
                                {{ $guest->bookings->whereIn('status', ['confirmed', 'checked_in'])->count() }}
                            </h3>
                            <small class="text-muted">Active</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking History -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i> Booking History</h5>
        </div>
        <div class="card-body">
            @if($guest->bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Booking Code</th>
                                <th>Room</th>
                                <th style="width: 120px; min-width:120px">Check-in</th>
                                <th style="width: 120px; min-width:120px">Check-out</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guest->bookings->sortByDesc('created_at') as $booking)
                            <tr>
                                <td><strong>{{ $booking->booking_code ?? 'N/A' }}</strong></td>
                                <td>{{ $booking->room->room_number ?? 'N/A' }}</td>
                                <td>{{ $booking->check_in_date }}</td>
                                <td>{{ $booking->check_out_date }}</td>
                                <td>TZS {{ number_format($booking->total_amount) }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $booking->status == 'confirmed' ? 'success' : 
                                        ($booking->status == 'checked_in' ? 'primary' : 
                                        ($booking->status == 'checked_out' ? 'info' : 
                                        ($booking->status == 'cancelled' ? 'danger' : 'warning'))) 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-info text-light">
                                        <i class="fas fa-eye"></i> <span class="d-none d-md-inline">View</span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-calendar-times fs-1"></i>
                    <p class="mt-3">This guest has no bookings yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection