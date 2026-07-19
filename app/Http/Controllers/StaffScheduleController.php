<?php

namespace App\Http\Controllers;

use App\Models\StaffSchedule;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Helpers\LogActivity;


class StaffScheduleController extends Controller
{
    /**
     * view staff schedules
     */
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');

        // load housekeepers
        $housekeepers = User::role('housekeeper') 
                    ->orderBy('name', 'asc')
                    ->get();

        // all shifts
        $shifts = Shift::orderBy('start_time', 'asc')->get();

        // today's schedules 
        $schedules = StaffSchedule::with(['user', 'shift'])
            ->whereDate('shift_date', $today)
            ->get();

        return view('staff-schedules.index', compact('housekeepers', 'shifts', 'schedules'));
    }

    /**
     * store staff schedule for a specific date range
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'shift_id'   => 'required|exists:shifts,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'notes'      => 'nullable|string',
        ]);

        // Multi-date range
        $period = CarbonPeriod::create($request->start_date, $request->end_date);
        
        $staff = User::findOrFail($request->user_id);
        $shift = Shift::findOrFail($request->shift_id);

        // Load each date within the range and insert into the database
        foreach ($period as $date) {
            // Prevent duplicate schedule for the same staff member on the same date
            StaffSchedule::firstOrCreate([
                'user_id'    => $request->user_id,
                'shift_date' => $date->format('Y-m-d'),
            ], [
                'shift_id'   => $request->shift_id,
                'notes'      => trim($request->notes),
            ]);
        }

        // Audit Logs
        LogActivity::log('Schedule Staff', 'Assigned a schedule to staff ' . $staff->name . ' from ' . $request->start_date . ' to ' . $request->end_date);

        return redirect()->route('staff-schedules.index')->with('success', 'Schedule assigned successfully!');
    }
}
