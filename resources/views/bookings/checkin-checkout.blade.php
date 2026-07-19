@extends('layouts.app')

@section('title', 'Check-in / Check-out')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Check-in / Check-out Management</h2>

    <div class="row">
        <!-- Today's Check-ins -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5>Today's Check-ins ({{ $pendingCheckIns->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($pendingCheckIns->count() > 0)
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Guest</th>
                                    <th>Room</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingCheckIns as $booking)
                                <tr>
                                    <td>{{ $booking->guest->full_name }}</td>
                                    <td><strong>{{ $booking->room->room_number }}</strong></td>
                                    <td>
                                        <form action="{{ route('bookings.checkin', $booking) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Check-in</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No pending check-ins today.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Today's Check-outs -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5>Today's Check-outs ({{ $pendingCheckOuts->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($pendingCheckOuts->count() > 0)
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Guest</th>
                                    <th>Room</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingCheckOuts as $booking)
                                <tr>
                                    <td>{{ $booking->guest->full_name }}</td>
                                    <td><strong>{{ $booking->room->room_number }}</strong></td>
                                    <td>
                                        <form action="{{ route('bookings.checkout', $booking) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">Check-out</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No pending check-outs today.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Check-ins / Check-outs -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5>Recent Check-ins</h5>
                </div>
                <div class="card-body">
                    @if($recentCheckIns->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentCheckIns as $booking)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $booking->guest->full_name }}</strong><br>
                                    <small class="text-muted">Room {{ $booking->room->room_number ?? 'N/A' }}</small>
                                </div>
                                <span class="badge bg-success">{{ $booking->check_in_date }}</span>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted text-center py-3">No recent check-ins.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5>Recent Check-outs</h5>
                </div>
                <div class="card-body">
                    @if($recentCheckOuts->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentCheckOuts as $booking)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $booking->guest->full_name }}</strong><br>
                                    <small class="text-muted">Room {{ $booking->room->room_number ?? 'N/A' }}</small>
                                </div>
                                <span class="badge bg-warning">{{ $booking->check_out_date }}</span>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted text-center py-3">No recent check-outs.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection