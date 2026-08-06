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
        $task->room->update([
            'status' => 'maintenance'
        ]);

        return redirect()->route('dashboard.housekeeper')
            ->with('success', 'Issue reported successfully. Room moved to maintenance.');
    }

    // Admin only - mark as fixed when maintenance issue is fixed 
    public function markAsFixed(MaintenanceLog $log)
    {
        // Security check
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Access denied.');
        }

        // Make sure room exists
        if (!$log->room) {
            return back()->with('error', 'Room not found for this maintenance log.');
        }

        $roomNumber = $log->room->room_number;

        try {
            DB::transaction(function () use ($log) {
                // Update the maintenance log
                $log->update([
                    'status'   => 'fixed',
                    'fixed_by' => auth()->id(),
                    'fixed_at' => now(),
                ]);

                // Update the room status
                $log->room->update([
                    'status' => 'dirty',   // Ready for final cleaning
                ]);
            });

            return back()->with('success', "Room {$roomNumber} has been marked as fixed and returned to cleaning queue.");
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}