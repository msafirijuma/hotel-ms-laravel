@extends('layouts.app')

@section('title', 'Dashboard - Hotel MS')

@section('content')
@extends('layouts.partials.navbar')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Dashboard</h2>
        <div>
            <span class="badge bg-primary fs-6">{{ ucfirst($user_role ?? 'User') }}</span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <!-- Total Rooms -->
        <div class="col-xl-3 col-md-6 col-sm-12">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="opacity-75">Total Room</h6>
                            <h2 class="mb-0">{{ $total_rooms ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-bed fs-1 opacity-75"></i>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('rooms.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                            <i class="fas fa-eye"></i> View Rooms
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="col-xl-3 col-md-6 col-sm-12">
            <div class="card border-0 shadow-sm h-100 bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="opacity-75">Total Bookings</h6>
                            <h2 class="mb-0">{{ $total_bookings ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-calendar-check fs-1 opacity-75"></i>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('bookings.index') }}" class="btn btn-light btn-sm text-success font-weight-bold">
                            <i class="fas fa-eye"></i> View Bookings
                    </a>
                </div>
            </div>
        </div>

        <!-- TotalGuest -->
        <div class="col-xl-3 col-md-6 col-sm-12">
            <div class="card border-0 shadow-sm h-100 bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="opacity-75">Total Guests</h6>
                            <h2 class="mb-0">{{ $total_guests ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-user-friends fs-1 opacity-75"></i>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('guests.index') }}" class="btn btn-light btn-sm text-warning font-weight-bold">
                            <i class="fas fa-eye"></i> View Guests
                    </a>
                </div>
            </div>
        </div>

        <!-- Revenue Today -->
        <div class="col-xl-3 col-md-6 col-sm-12">
            <div class="card border-0 shadow-sm h-100 bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="opacity-75">Revenue Today</h6>
                            <h2 class="mb-0">Tsh {{ number_format($total_revenue_today ?? 0) }}</h2>
                        </div>
                        <i class="bi bi-currency-exchange fs-1 opacity-75"></i>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('reports.index') }}" class="btn btn-light btn-sm text-info font-weight-bold">
                            <i class="fas fa-eye"></i> View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="row mt-5 pb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-calendar-check me-2"></i>Recent Bookings
                    </h6>
                    <a href="{{ route('bookings.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                    <i class="fas fa-eye"></i> View All Booking
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($recentBookings) && $recentBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Guest</th>
                                        <th>Room</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $booking)
                                    <tr>
                                        <td>{{ $booking->guest->full_name ?? 'N/A' }}</td>
                                        <td>{{ $booking->room->room_number ?? 'N/A' }}</td>
                                        <td>{{ $booking->check_in_date }}</td>
                                        <td>{{ $booking->check_out_date }}</td>
                                        <td>TSh {{ number_format($booking->total_amount) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No recent bookings yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="row mt-4 pb-5">
        <div class="col-12">
            <div class="card shadow-sm rounded-3 mt-4">
                <div class="card-header py-3 bg-dark text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-cash-register me-2"></i>Recent Payments</h6>
                    <a href="{{ route('payments.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                    <i class="fas fa-eye"></i> View All Payments
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Invoice No.</th>
                                    <th>Guest</th>
                                    <th>Method</th>
                                    <th>Amount Paid</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $rp)
                                <tr>
                                    <td class="font-monospace text-primary fw-bold">{{ $rp->invoice_number }}</td>
                                    <td>
                                        <strong>{{ $rp->booking->guest->full_name ?? 'Guest' }}</strong>
                                        <small class="text-muted d-block" style="font-size: 11px;">Room: {{ $rp->booking->room->room_number }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border text-uppercase" style="font-size: 10px;">
                                            {{ str_replace('_', ' ', $rp->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="font-monospace text-success fw-bold">
                                        TZS {{ number_format($rp->amount_paid, 0) }}
                                    </td>
                                    <td class="small text-muted font-monospace">
                                        {{ \Carbon\Carbon::parse($rp->created_at)->diffForHumans() }} {{-- e.g., '5 mins ago' --}}
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-white">{{ $rp->status }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4 small">No any recent payments yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's total booking and pending bookings -->
        <div class="row mt-2 pb-2">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3 bg-gradient-dark text-dark">
                        <h6 class="m-0 font-weight-bold">Today's Booking & Guests</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 text-center">
                            <!-- Today's Bookings -->
                            <div class="col-12 col-md-3">
                                <a href="{{ route('bookings.index') }}" class="btn btn-primary btn-lg w-100 py-4 shadow-sm text-decoration-none">
                                    <i class="fas fa-calendar-day fa-2x d-block mb-2"></i>
                                    Today's Bookings ({{ $today_bookings }})
                                </a>
                            </div>

                            <!-- Pending Bookings -->
                            <div class="col-12 col-md-3">
                                <a href="{{ route('bookings.index', ['status' => 'pending']) }}" class="btn btn-warning btn-lg w-100 py-4 shadow-sm text-decoration-none text-dark">
                                    <i class="fas fa-hourglass-half fa-2x d-block mb-2"></i>
                                    Pending Bookings ({{ $pending_bookings }})
                                </a>    
                            </div>

                            <!-- Active/Confirmed Bookings -->
                            <div class="col-12 col-md-3">
                                <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}" class="btn btn-info btn-lg w-100 py-4 shadow-sm text-decoration-none text-white">
                                    <i class="fas fa-list fa-2x d-block mb-2"></i>
                                    Active Bookings ({{ $active_bookings }})
                                </a>
                            </div>

                            <!-- New Guests Today -->
                            <div class="col-12 col-md-3">
                                <a href="{{ route('guests.index') }}" class="btn btn-success btn-lg w-100 py-4 shadow-sm text-decoration-none">
                                    <i class="fas fa-user-friends fa-2x d-block mb-2"></i>
                                    New Guests Today ({{ $today_guests }})
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Stats & Room Status Overview -->
        <div class="row g-4 mt-4">
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3 bg-gradient-primary text-dark">
                        <h6 class="m-0 font-weight-bold">Revenue By Month (This Year)</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3 bg-gradient-warning text-dark">
                        <h6 class="m-0 font-weight-bold">Room Status Overview</h6>
                        <small>Total rooms: {{ $total_rooms }}</small>
                    </div>
                    <div class="card-body">
                        <canvas id="roomStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>   
        
        <!-- Charts Row -->
        <div class="row mt-4">
            <div class="col-lg-8 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3 bg-gradient-info text-dark">
                        <h6 class="m-0 font-weight-bold">Daily Revenue (Last 30 Days)</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="dailyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-gradient-dark text-dark">
                    <h6 class="m-0 font-weight-bold">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('bookings.create') }}" class="btn btn-success btn-lg w-100 py-4">
                                <i class="fas fa-plus fa-2x d-block mb-2"></i>
                                Add Booking
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('bookings.checkin-checkout') }}" class="btn btn-primary btn-lg w-100 py-4">
                                <i class="fas fa-key fa-2x d-block mb-2"></i>
                                Check-in / Out
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('bookings.index') }}" class="btn btn-info btn-lg w-100 py-4">
                                <i class="fas fa-list fa-2x d-block mb-2"></i>
                                Bookings List
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('reports.index') }}" class="btn btn-warning btn-lg w-100 py-4">
                                <i class="fas fa-chart-bar fa-2x d-block mb-2"></i>
                                Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomStatusCtx = document.getElementById('roomStatusChart').getContext('2d');
    
    new Chart(roomStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Available', 'Occupied', 'Dirty', 'Maintenance'],
            datasets: [{
                data: [
                    {{ $available_rooms ?? 0 }},
                    {{ $occupied_rooms ?? 0 }},
                    {{ $dirty_rooms ?? 0 }},
                    {{ $maintenance_rooms ?? 0 }}
                ],
                backgroundColor: ['#1cc88a', '#007bff', '#dc3545', '#858796'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { 
                        boxWidth: 14, 
                        padding: 18,
                        font: { size: 13 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw;
                        }
                    }
                }
            }
        }
    });
});
</script>
<script>
// Monthly Revenue Chart
const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: @json($months ?? []),
        datasets: [{
            label: 'Mapato (TZS)',
            data: @json($monthly_revenue ?? []),
            backgroundColor: 'rgba(78, 115, 223, 0.7)',
            borderColor: '#4e73df',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => 'TZS ' + v.toLocaleString() } }
        }
    }
});

// Daily Revenue Chart
const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: @json($dates ?? []),
        datasets: [{
            label: 'Mapato (TZS)',
            data: @json($daily_revenue ?? []),
            borderColor: '#36b9cc',
            backgroundColor: 'rgba(54, 185, 204, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => 'TZS ' + v.toLocaleString() } }
        }
    }
});
</script>
<script>
// Auto Refresh Every 300 Seconds
setInterval(() => location.reload(), 300000);
</script>
@endsection
