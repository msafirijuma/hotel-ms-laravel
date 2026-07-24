<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('roomType')->latest()->paginate(15);
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $roomTypes = RoomType::all();
        return view('rooms.create', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number'   => 'required|string|max:20|unique:rooms,room_number',
            'room_type_id'  => 'required|exists:room_types,id',
            'status'        => 'required|in:available,occupied,maintenance,dirty,cleaning',
            'notes'         => 'nullable|string|max:500',
            'floor'         => 'nullable|string',
        ], [
            'room_number.unique' => 'Room number already exists.',
            'room_type_id.exists' => 'Please select a valid room type.',
        ]);

        Room::create($validated);

        LogActivity::log('CREATE ROOM', 'Has created Room No. : ' . $request->room_number . ' status.');

        return redirect()->route('rooms.index')
            ->with('success', 'Room No. ' . $request->room_number . ' created successfully!');
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::all();
        return view('rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number'   => 'required|string|max:20|unique:rooms,room_number,' . $room->id,
            'room_type_id'  => 'required|exists:room_types,id',
            'floor'        => 'nullable|string|max:50',
            'status'        => 'required|in:available,occupied,maintenance,dirty',
            'notes'         => 'nullable|string|max:500',
        ], [
            'room_number.unique' => 'Room number already exists.',
        ]);

        $room->update($validated);

        LogActivity::log('UPDATE ROOM', 'Has updated Room No. ' . $room->room_number . ' successfully.');

        return redirect()->route('rooms.index')
            ->with('success', 'Room No. ' . $room->room_number . ' updated successfully!');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        LogActivity::log('DELETE ROOM', 'Has deleted Room No. ' . $room->room_number . ' successfully.');

        return redirect()->route('rooms.index')
            ->with('success', 'Room No. ' . $room->room_number .  ' deleted successfully!');
    }

    public function show(Room $room)
    {
        // Load roomType for image and price
        $room->load('roomType');

        return view('rooms.show', compact('room'));
    }

    /**
     * Update room status
     */
    public function updateStatus(Request $request, Room $room)
    {
        $request->validate([
            'status' => 'required|in:available,occupied,dirty,maintenance,cleaning',
        ]);

        $room->update([
            'status' => $request->status
        ]);

        LogActivity::log('UPDATE ROOM STATUS', 'Has updated status of Room No. ' . $room->room_number . ' to ' . ucfirst($request->status));

        return back()->with('success', 'Status of room No. ' . $room->room_number . ' updated to ' . ucfirst($request->status));
    }
}
