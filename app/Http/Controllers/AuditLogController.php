<?php

namespace App\Http\Controllers;

use App\Models\AuditLog; 
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        // Load user
        $query = AuditLog::with('user')->latest();

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Load all data for easy querying
        $logs = $query->get();

        return view('audit-logs.index', compact('logs'));
    }
}
