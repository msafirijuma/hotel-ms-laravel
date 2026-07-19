@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white pt-3 pb-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>System Audit Logs (Activity History)</h5>
        </div>
        
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle mb-0" id="auditLogsTable" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Activity</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>Browser</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-nowrap text-secondary font-monospace" style="font-size: 13px;">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}
                            </td>
                            <td>
                                <strong class="text-dark">{{ $log->user->name ?? 'System/Guest' }}</strong>
                                <small class="text-muted d-block" style="font-size: 11px;">
                                    {{ $log->user ? ($log->user->getRoleNames()->first() ?? 'User') : '—' }}
                                </small>
                            </td>
                            <td>
                                <!-- Set badge color based on activity type -->
                                <span class="badge px-3 py-1.5 text-uppercase bg-{{ 
                                    Str::contains(strtolower($log->activity), ['create', 'add']) ? 'success' : 
                                    (Str::contains(strtolower($log->activity), ['update', 'edit', 'change']) ? 'warning text-dark' : 
                                    (Str::contains(strtolower($log->activity), ['delete', 'remove', 'cancel']) ? 'danger' : 'info')) 
                                }}">
                                    {{ $log->activity }}
                                </span>
                            </td>
                            <td class="text-muted" style="font-size: 14px;">{{ $log->description }}</td>
                            <td class="text-secondary font-monospace small">{{ $log->ip_address ?? '—' }}</td>
                            <td class="text-truncate text-muted small" style="max-width: 150px;" title="{{ $log->user_agent }}">
                                {{ Str::limit($log->user_agent, 30) }}
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
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#auditLogsTable').DataTable({
        "order": [[0, "desc"]],
        "pageLength": 25,
        "responsive": true,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "No records available",
            "zeroRecords": "No matching records found",
            "lengthMenu": "Show _MENU_ entries",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Prev"
            }
        }
    });
});
</script>
@endsection