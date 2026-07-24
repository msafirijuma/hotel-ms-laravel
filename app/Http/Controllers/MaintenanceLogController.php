<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use App\Models\Room;
use App\Models\HousekeepingTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MaintenanceLogController extends Controller
{
    // Index view
    public function index()
    {
        $logs = MaintenanceLog::with(['room.roomType', 'reportedBy'])
            ->latest()
            ->paginate(15);

        return view('maintenance-logs.index', compact('logs'));
    }

    // Report issue 
    public function reportIssue(Request $request, HousekeepingTask $task)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */

        $validated = $request->validate([
            'issue_description' => 'required|string|max:500'
        ]);

        // Create Maintenance Log
        MaintenanceLog::create([
            'room_id' => $task->room_id,
            'reported_by' => auth()->id(),
            'issue_description' => $validated['issue_description'],
            'status' => 'pending'
        ]);

        // Mark task as completed
        $task->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        // Room to maintenance
        $task->room->update(['status' => 'maintenance']);

        return redirect()->route('dashboard.housekeeping')
            ->with('success', 'Issue reported successfully. Room moved to maintenance.');
    }

    // Admin only - mark as fixed when maintenance issue is fixed 
    public function markAsFixed(MaintenanceLog $log)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */

        // Checking if role === admin
        if (!$user->hasRole('admin') && $user->role !== 'admin') {
            abort(403, 'Access denied.');
        }

        // Checking if room exists
        if (!$log->room) {
            return back()->with('error', 'Room not found for this log.');
        }

        $roomNumber = $log->room->room_number;

        DB::transaction(function () use ($log) {
            // Update maintenance log
            $log->update([
                'status' => 'fixed',
                'fixed_by' => auth()->id(),
                'fixed_at' => now()
            ]);

            // Room back to dirty for final cleaning
            $log->room->update([
                'status' => 'dirty',
                'notes' => 'Maintenance issue fixed. Ready for final cleaning.'
            ]);
        });

        return back()->with('success', "Maintenance for Room {$roomNumber} has been fixed! Room returned to cleaning queue.");
    }
}