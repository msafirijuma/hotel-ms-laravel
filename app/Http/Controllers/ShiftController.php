<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use App\Helpers\LogActivity; 

class ShiftController extends Controller
{
    /**
     * show all shifts
     */
    public function index()
    {
        $shifts = Shift::orderBy('start_time', 'asc')->get();
        return view('shifts.index', compact('shifts'));
    }

    /**
     * save new shift to database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $shift = Shift::create([
            'name'       => trim($request->name),
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
        ]);

        // Audit Logs
        LogActivity::log('Create Shift', 'Adding new shift: ' . $shift->name);

        return redirect()->route('shifts.index')->with('success', 'New shift added!');
    }

    public function edit(Shift $shift)
    {
        return view('shifts.edit', compact('shift'));
    }

    /**
     * save changes to database
     */
    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $shift->update([
            'name'       => trim($request->name),
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
        ]);

        // Audit Logs
        LogActivity::log('Update Shift', 'Updated shift: ' . $shift->name);

        return redirect()->route('shifts.index')->with('success', 'Shift updated successfully!');
    }

    /**
     * delete shift from database
     */
    public function destroy(Shift $shift)
    {
        $shiftName = $shift->name;
        $shift->delete();

        // Audit Logs
        LogActivity::log('Delete Shift', 'Deleted shift: ' . $shiftName);

        return redirect()->route('shifts.index')->with('success', 'Shift deleted succesfully!');
    }
}
