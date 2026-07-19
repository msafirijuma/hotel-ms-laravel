<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::latest()->paginate(15);
        return view('guests.index', compact('guests'));
    }

    public function create()
    {
        return view('guests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email'     => 'nullable|email|unique:guests,email',
            'phone'     => 'required|string|unique:guests,phone',
            'id_number' => 'required|string|unique:guests,id_number',
            'address'   => 'nullable|string',
            'country'   => 'nullable|string|max:100',
        ]);

        Guest::create($validated);
        return redirect()->route('guests.index')
                         ->with('success', 'Guest added successfully!');
    }

    public function edit(Guest $guest)
    {
        return view('guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email'     => 'nullable|email|unique:guests,email,' . $guest->id,
            'phone'     => 'required|string|unique:guests,phone,' . $guest->id,
            'id_number' => 'required|string|unique:guests,id_number,' . $guest->id,
            'address'   => 'nullable|string',
            'country'   => 'nullable|string|max:100',
        ]);

        $guest->update($validated);

        return redirect()->route('guests.index')
                         ->with('success', 'Guest updated successfully!');
    }

    public function destroy(Guest $guest)
    {
        // If guest has booking, do not delete
        if ($guest->bookings()->count() > 0) {
            return redirect()->route('guests.index')
                             ->with('error', 'Cannot delete guest with active bookings.');
        }

        $guest->delete();

        return redirect()->route('guests.index')
                         ->with('success', 'Guest deleted successfully!');
    }
}