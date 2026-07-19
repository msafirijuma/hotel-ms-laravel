@extends('layouts.app')

@section('title', "Hotel's Reports")

@section('content')
<div class="container-fluid">
    <!-- Header & Filter Form -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h2 class="mb-0 text-gray-800"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Hotel's Reports (Live Update)</h2>
        
        <div>
            <form action="{{ route('reports.index') }}" method="GET" class="d-flex gap-2">
                <!-- Month Filter -->
                <select name="month" class="form-select w-auto">
                    @for ($m = 1; $m <= 12; $m++)
                        @php $monthVal = sprintf('%02d', $m); @endphp
                        <option value="{{ $monthVal }}" {{ $monthVal == $month ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>

                <!-- Year Filter -->
                <select name="year" class="form-select w-auto">
                    @for ($y = date('Y')-2; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <!-- Occupancy Rate -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-primary border-4 shadow-sm h-100">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" style="font-size: 12px;">Occupancy Rate</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $occupancy_rate }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-pie-chart-fill text-muted fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-success border-4 shadow-sm h-100">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1" style="font-size: 12px;">
                                Revenue {{ date('F Y', mktime(0, 0, 0, $month, 1)) }}
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">TZS {{ number_format($month_revenue, 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-coin text-muted fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Guests -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-info border-4 shadow-sm h-100">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1" style="font-size: 12px;">Total Guests</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $total_guests }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill text-muted fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Staying -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-warning border-4 shadow-sm h-100">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1" style="font-size: 12px;">Average Staying</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ number_format($avg_stay, 1) }} days</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar3 text-muted fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <!-- Daily Revenue Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3 bg-light border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-bar-chart-line-fill me-1"></i> Daily Revenue (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart" style="min-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3 bg-light border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-graph-up me-1"></i> Revenue By Months ({{ $year }})</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart" style="min-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Daily Revenue (Last 30 days)
    const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($daily_labels) !!},
            datasets: [{
                label: 'Revenue (TZS)',
                data: {!! json_encode($daily_data) !!},
                backgroundColor: 'rgba(13, 202, 240, 0.1)',
                borderColor: 'rgba(13, 202, 240, 1)',
                borderWidth: 2,
                borderWidth: 2,
                fill: true,
                tension: 0.3
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

    // Monthly Revenue (This Year)
    const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthly_labels) !!},
            datasets: [{
                label: 'Revenue (TZS)',
                data: {!! json_encode($monthly_data) !!},
                backgroundColor: 'rgba(13, 110, 253, 0.85)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script>
// Auto Refresh Every 300 Seconds
setInterval(() => location.reload(), 300000);
</script>
@endsection
