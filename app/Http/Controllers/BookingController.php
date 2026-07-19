<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use \Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Helpers\LogActivity;


class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['guest', 'room', 'payments'])
            ->latest()
            ->paginate(15);
        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $guests = Guest::all();

        // Only fetch rooms that are available for booking
        $rooms = Room::with('roomType')
            ->where('status', 'available')
            ->orderBy('room_number', 'asc')
            ->get();
        return view('bookings.create', compact('guests', 'rooms'));
    }

    public function store(Request $request)
    {
        // Validate data
        $request->validate([
            'check_in_date'  => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'room_id'        => 'required|exists:rooms,id',
            'adults'         => 'required|integer|min:1',
            'children'       => 'nullable|integer|min:0',
            'guest_name'     => 'required|string|max:255',
            'guest_phone'    => 'required|string|max:20',
            'guest_email'    => 'nullable|email|max:255',
            'notes'          => 'nullable|string',
        ]);

        // Start a database transaction to ensure simultaneous operations are handled safely
        DB::beginTransaction();

        try {
            // Find or create the guest based on phone number
            $guest = Guest::firstOrCreate(
                ['phone' => $request->guest_phone],
                [
                    'full_name' => $request->guest_name,
                    'email'     => $request->guest_email,
                    'id_type'   => null,
                    'id_number' => '—',
                ]
            );

            // Find the room and calculate the total amount based on the number of nights and room price
            $room = Room::with('roomType')->findOrFail($request->room_id);

            // Check if the room is still available before proceeding
            if ($room->status !== 'available') {
                return back()->withInput()->withErrors(['room_id' => 'Sorry, this room is already booked.']);
            }

            $checkIn = Carbon::parse($request->check_in_date);
            $checkOut = Carbon::parse($request->check_out_date);
            $nights = $checkIn->diffInDays($checkOut);
            $nights = $nights == 0 ? 1 : $nights; // Nights should be at least 1

            $price_per_night = $room->roomType->price_per_night ?? 0;
            $total_amount = $nights * $price_per_night;

            // Booking code
            $booking_code = 'BK' . strtoupper(substr(md5(time() . $request->room_id), 0, 6));

            // Save Booking
            $booking = Booking::create([
                'booking_code'   => $booking_code,
                'guest_id'       => $guest->id,
                'room_id'        => $room->id,
                'check_in_date'  => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'adults'         => $request->adults,
                'children'       => $request->children ?? 0,
                'total_amount'   => $total_amount,
                'notes'          => $request->notes,
                'status'         => 'pending', // default status till confirmed and paid
            ]);

            // Status change for the room to occupied if the booking is confirmed immediately
            $room->update(['status' => 'occupied']);

            DB::commit(); // Commit all changes on the database
            LogActivity::log('Create Booking', 'Has created a booking with a code: ' . $booking_code);
            return redirect()->route('bookings.index')
                ->with('success', 'New booking created successfully! Booking code: ' . $booking_code);
        } catch (\Exception $e) {
            DB::rollBack(); // Error occurred, rollback the transaction
            return back()->withInput()->withErrors(['error' => 'An error occured: ' . $e->getMessage()]);
        }
    }

    public function show(Booking $booking)
    {
        // Load models related to the booking for detailed view
        $booking->load(['guest', 'room.roomType', 'payments']);
        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $booking->load(['guest', 'room.roomType']);
        return view('bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'check_out_date' => 'required|date|after_or_equal:' . $booking->check_in_date,
            'adults'         => 'required|integer|min:1',
            'children'       => 'nullable|integer|min:0',
            'notes'          => 'nullable|string',
        ]);

        // Total amounts after updating
        $checkIn = Carbon::parse($booking->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $nights = $checkIn->diffInDays($checkOut);
        $nights = $nights == 0 ? 1 : $nights;

        $price_per_night = $booking->room->roomType->price_per_night ?? 0;
        $total_amount = $nights * $price_per_night;

        // Save all changes to the booking
        $booking->update([
            'check_out_date' => $request->check_out_date,
            'adults'         => $request->adults,
            'children'       => $request->children ?? 0,
            'total_amount'   => $total_amount,
            'notes'          => $request->notes,
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking ' . $booking->booking_code . ' updated successfully!');
    }

    // Check-in and Check-out functionality
    public function checkInOut()
    {
        $today = now()->format('Y-m-d');

        // Recent Check-ins & Check-outs
        $recentCheckIns = Booking::with(['guest', 'room'])
            ->where('status', 'checked_in')
            ->orWhere('status', 'confirmed')
            ->latest('check_in_date')
            ->take(5)
            ->get();

        $recentCheckOuts = Booking::with(['guest', 'room'])
            ->where('status', 'checked_out')
            ->latest('check_out_date')
            ->take(5)
            ->get();

        // Pending Check-ins
        $pendingCheckIns = Booking::with(['guest', 'room'])
            ->where('check_in_date', $today)
            ->where('status', 'confirmed')
            ->get();

        // Pending Check-outs
        $pendingCheckOuts = Booking::with(['guest', 'room'])
            ->where('check_out_date', $today)
            ->where('status', 'checked_in')
            ->get();

        return view('bookings.checkin-checkout', compact('pendingCheckIns', 'pendingCheckOuts', 'recentCheckIns', 'recentCheckOuts'));
    }

    /**
     * Check-in Guest
     */
    public function checkin(Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Only confirmed bookings can be checked in.');
        }

        $booking->update([
            'status' => 'checked_in',
            'check_in_date' => now()->format('Y-m-d') // Update actual check-in date
        ]);

        // Update room status
        $booking->room->update(['status' => 'occupied']);

        return redirect()->route('bookings.checkin-checkout')
            ->with('success', 'Guest checked in successfully!');
    }

    /**
     * Check-out Guest
     */
    public function checkout(Booking $booking)
    {
        if ($booking->status !== 'checked_in') {
            return redirect()->back()->with('error', 'Only checked-in bookings can be checked out.');
        }

        $booking->update([
            'status' => 'checked_out',
            'check_out_date' => now()->format('Y-m-d')
        ]);

        // Update room status back to available
        $booking->room->update(['status' => 'available']);

        return redirect()->route('bookings.checkin-checkout')
            ->with('success', 'Guest checked out successfully!');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Hali ya booking imesasishwa kuwa ' . ucfirst($request->status));
    }


    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully!');
    }
}
