<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

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
        'status'        => 'required|in:available,occupied,maintenance,dirty',
        'notes'         => 'nullable|string|max:500',
    ], [
        'room_number.unique' => 'Room number already exists.',
        'room_type_id.exists' => 'Please select a valid room type.',
    ]);

    Room::create($validated);

    return redirect()->route('rooms.index')
                     ->with('success', 'Room added successfully!');
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

    return redirect()->route('rooms.index')
                     ->with('success', 'Room updated successfully!');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')
                         ->with('success', 'Room deleted successfully!');
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
        'status' => 'required|in:available,occupied,dirty,maintenance',
        ]);

        $room->update([
            'status' => $request->status
        ]);
        
        return back()->with('success', 'Status of room No. ' . $room->room_number . ' updated to ' . ucfirst($request->status));
        }
}