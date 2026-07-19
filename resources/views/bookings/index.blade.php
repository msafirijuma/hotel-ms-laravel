@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
<div class="card shadow-sm">
    <!-- Header -->
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>All Bookings</h5>
        <a href="{{ route('bookings.create') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
            <i class="fas fa-plus"></i> New Booking
        </a>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered" id="bookingsTable" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Booking Code</th>
                        <th>Visitor</th>
                        <th>Room</th>
                        <th>Booking Status</th>
                        <th>Change Status</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    @php
                        // Total amount paid using relationship with payment
                        $total_paid = $booking->payments ? $booking->payments->sum('amount_paid') : 0;
                        $remaining = $booking->total_amount - $total_paid;
                    @endphp
                    <tr>
                        <!-- # Laravel Pagination -->
                        <td>{{ $bookings->firstItem() + $loop->index }}</td>

                        <!-- Booking Code -->
                        <td><strong>{{ $booking->booking_code ?? $booking->id }}</strong></td>

                        <!-- Visitor -->
                        <td>
                            <strong>{{ $booking->guest->full_name ?? 'N/A' }}</strong>
                            <small class="text-muted d-block">{{ $booking->guest->phone ?? '' }}</small>
                        </td>

                        <!-- Room -->
                        <td>
                            <span class="badge bg-secondary">No. {{ $booking->room->room_number ?? '—' }}</span>
                            <small class="d-block text-muted">{{ $booking->room->room_type ?? '' }}</small>
                        </td>

                        <!-- Booking Status -->
                        <td>
                            <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>

                        <!-- Change Status -->
                        <td>
                            <form action="{{ route('bookings.update-status', $booking->id) }}" method="POST" enctype="multipart/form-data" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm" style="min-width: 120px;">
                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="confirmed" {{ $booking->status == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                                    <option value="confirmed" {{ $booking->status == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>

                        <!-- Amount -->
                        <td><strong>TZS {{ number_format($booking->total_amount) }}</strong></td>

                        <!-- Payment Status -->
                        <td>
                            @if($total_paid >= $booking->total_amount)
                                <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Paid</span>
                            @elseif($total_paid > 0 && $total_paid < $booking->total_amount)
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock-history"></i> Partial</span>
                                <small class="text-danger d-block small" style="font-size: 11px;">Due: {{ number_format($remaining) }}</small>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-exclamation-triangle-fill"></i> Unpaid</span>
                            @endif
                        </td>

                        <!-- Actions Buttons -->
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center align-items-center">
                                
                                @if($remaining > 0)
                                    <a href="{{ route('payments.create', $booking->id) }}" class="btn btn-sm btn-success fw-bold px-2 py-1" title="Collect Payment">
                                        Pay
                                    </a>
                                @else
                                    @if($booking->payments && $booking->payments->count() > 0)
                                        <a href="{{ route('payments.invoice', $booking->payments->last()->id) }}" class="btn btn-sm btn-outline-primary py-1" title="View Invoice">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                    @endif
                                @endif

                                <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-info text-white py-1" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-warning text-white py-1" title="Edit Booking">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="d-inline m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger py-1" onclick="return confirm('Delete this booking?')" title="Delete Booking">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Laravel Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
