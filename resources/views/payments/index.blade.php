@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="container-fluid pt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
            <h5>
                <i class="fas fa-money-bill me-2"></i>Payments
            </h5>
            <a href="{{ route('payments.create') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                <i class="fas fa-plus"></i> Record Payment
        </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle" id="paymentsTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $payment->invoice_number }}</strong></td>
                            <td>{{ $payment->payment_date?->format('d M Y H:i') ?? $payment->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $payment->booking->guest->full_name ?? 'N/A' }}</td>
                            <td>{{ $payment->booking->room->room_number ?? 'N/A' }}</td>
                            <td><strong>TZS {{ number_format($payment->amount_paid, 2) }}</strong></td>
                            <td><span class="badge bg-secondary">{{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}</span></td>
                            <td>
                                <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('payments.invoice', $payment) }}" class="btn btn-sm btn-outline-dark" title="Invoice">
                                    <i class="fas fa-receipt"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No payments found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection