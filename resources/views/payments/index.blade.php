@extends('layouts.app')

@section('title', 'All Payments')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-money-check-alt me-2"></i>Hotel Payments & Transactions Log</h5>
        </div>
        
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle mb-0" id="paymentsTable" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>Invoice No.</th>
                            <th>Guest Name</th>
                            <th>Room</th>
                            <th>Method</th>
                            <th>Amount Paid (TZS)</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $pay)
                        <tr>
                            <td class="font-monospace fw-bold text-primary">{{ $pay->invoice_number }}</td>
                            <td>
                                <strong>{{ $pay->booking->guest->full_name ?? 'Mgeni' }}</strong>
                                <small class="text-muted d-block" style="font-size: 11px;">Code: {{ $pay->booking->booking_code }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary font-weight-bold">Room {{ $pay->booking->room->room_number }}</span>
                                <small class="text-muted d-block mt-0.5" style="font-size: 11px;">{{ $pay->booking->room->roomType->type_name ?? 'Room' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border text-uppercase font-weight-bold">
                                    {{ str_replace('_', ' ', $pay->payment_method) }}
                                </span>
                            </td>
                            <td class="text-start font-monospace fw-bold text-success">
                                {{ number_format($pay->amount_paid, 2) }}
                            </td>
                            <td class="small text-muted font-monospace">
                                {{ \Carbon\Carbon::parse($pay->payment_date)->format('d/m/Y H:i') }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success font-weight-bold">{{ $pay->status }}</span>
                            </td>
                            <td class="text-center">
                                <!-- View receipt -->
                                <a href="{{ route('payments.invoice', $pay->id) }}" onclick="triggerReceipt(event, this.href)" class="btn btn-sm btn-outline-primary fw-bold" title="View & Print Receipt">
                                    <i class="fas fa-file-invoice"></i> Receipt
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Display background process spinner
    function showPageLoader(message) {
        Swal.fire({
            title: 'Generating Receipt',
            text: message,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // Loading receipt
    function triggerReceipt(event, url) {
        event.preventDefault(); 
        showPageLoader('Please be patient while a receipt is being prepared...');
        window.location.href = url;
    }
</script>
@endsection
