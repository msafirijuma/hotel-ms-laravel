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
        LogActivity::log('CREATE SCHEDULE', 'Has assigned a schedule to staff ' . $staff->name . ' from ' . $request->start_date . ' to ' . $request->end_date);

        return redirect()->route('staff-schedules.index')->with('success', 'Schedule assigned successfully for staff ' . $staff->name);
    }

    /**
     * Show the form for editing the specified schedule profile.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Find specific schedule record 
        $schedule = StaffSchedule::findOrFail($id);

        // Load housekeepers & shifts
        $housekeepers = User::orderBy('name', 'asc')->get();
        $shifts = Shift::orderBy('start_time', 'asc')->get();

        return view('staff-schedules.edit', compact('schedule', 'housekeepers', 'shifts'));
    }

    /**
     * Update the specified schedule
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'shift_id'   => 'required|exists:shifts,id',
            'shift_date' => 'required|date',
            'notes'      => 'nullable|string|max:500',
        ]);

        $schedule = StaffSchedule::findOrFail($id);

        // Check if there is another duplicate schedule entry for this user on the new date
        $duplicate = StaffSchedule::where('user_id', $request->user_id)
            ->where('shift_date', $request->shift_date)
            ->where('id', '!=', $id)
            ->exists();

        if ($duplicate) {
            return back()->withInput()->with('error', 'This staff member is already scheduled on this date.');
        }

        // Updating db
        $schedule->update([
            'user_id'    => $request->user_id,
            'shift_id'   => $request->shift_id,
            'shift_date' => $request->shift_date,
            'notes'      => trim($request->notes),
        ]);

        $staffName = $schedule->user->name ?? 'Staff';
        $formattedDate = Carbon::parse($request->shift_date)->format('d/m/Y');

        // Audit Logs
        LogActivity::log('UPDATE SCHEDULE', "Has update schedule duty for {$staffName} on {$formattedDate}");

        // Redirect back index
        return redirect()->route('staff-schedules.index')->with('success', "{$staffName}'s schedule updated successfully.");
    }


    /**
     * Remove the specified staff schedule 
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Find the specific schedule entry or fail with 404
        $schedule = StaffSchedule::with('user')->findOrFail($id);

        // Store staff name and date for logging purposes
        $staffName = $schedule->user->name ?? 'Staff';
        $shiftDate = Carbon::parse($schedule->shift_date)->format('d/m/Y');

        // Delete the record from the database
        $schedule->delete();

        // Audit Logs
        LogActivity::log('DELETE SCHEDULE', "Has removed shift duty for {$staffName} on date {$shiftDate}");

        // Redirect back 
        return back()->with('success', "Schedule duty for {$staffName} on {$shiftDate} has been removed successfully!");
    }
}
