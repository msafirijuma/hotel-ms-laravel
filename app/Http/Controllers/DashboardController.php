<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Booking;
use App\Models\User;
use App\Models\HousekeepingTask;
use App\Models\StaffSchedule;
use \App\Helpers\LogActivity;
use \App\Models\Payment;

class DashboardController extends Controller
{
    /**
     * main index view
     */
    public function index()
    {
        /** @var User $user */
        $user = auth::user();

        // get user role
        $role = $user->getRoleNames()->first() ?? 'user';
        $today = Carbon::today()->format('Y-m-d');

        // ==========================================
        // HOUSEKEEPER
        // ==========================================
        if ($user->hasRole('housekeeper')) {
            // Load housekeeping activities (stats) only
            $tasks = HousekeepingTask::select('housekeeping_tasks.*', 'rooms.room_number', 'room_types.name')
                ->selectRaw('TIMESTAMPDIFF(HOUR, housekeeping_tasks.created_at, NOW()) as hours_since_checkout')
                ->join('rooms', 'housekeeping_tasks.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('housekeeping_tasks.assigned_to', $user->id)
                ->whereIn('housekeeping_tasks.status', ['pending', 'in_progress'])
                ->orderBy('housekeeping_tasks.created_at', 'desc')
                ->get();

            $total_tasks = $tasks->count();
            $completed_today = HousekeepingTask::where('assigned_to', $user->id)
                ->where('status', 'completed')
                ->whereDate('completed_at', Carbon::today())
                ->count();

            $recentTasks = HousekeepingTask::with('room.roomType')
                ->where('assigned_to', Auth::id())
                ->where('status', 'completed')
                ->latest('completed_at')
                ->get();

            // Rooms
            $rooms = Room::with('roomType')
                ->where('status', 'dirty')
                ->orderBy('room_number')
                ->get();

            // Shift
            $schedules = StaffSchedule::with('shift')
                ->where('user_id', Auth::id())
                ->whereDate('shift_date', '>=', \Carbon\Carbon::today())
                ->orderBy('shift_date', 'asc')
                ->get();

            return view('dashboard.housekeeper', compact('tasks', 'recentTasks', 'total_tasks', 'completed_today', 'role', 'rooms', 'schedules'));
        }

        // ==========================================
        // ADMIN / RECEPTIONIST 
        // ==========================================

        // Revenue By Month (This Year)
        $currentYear = now()->year;
        $months = [];
        $monthly_revenue = [];

        for ($m = 1; $m <= 12; $m++) {
            $months[] = date('M', mktime(0, 0, 0, $m, 1));
            $revenue = Booking::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $m)
                ->sum('total_amount');
            $monthly_revenue[] = $revenue;
        }

        // Daily Revenue (Last 30 Days)
        $dates = [];
        $daily_revenue = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates[] = $date->format('d M');
            $revenue = Booking::whereDate('created_at', $date)->sum('total_amount');
            $daily_revenue[] = $revenue;
        }

        // Admin and Receptionist data
        $data = [
            'user_role' => $role,
            'user_name' => $user->name,

            // Rooms, Bookings and Guests stats
            'total_rooms' => Room::count(),
            'total_bookings' => Booking::count(),
            'total_guests' => Guest::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'occupied_rooms' => Room::where('status', 'occupied')->count(),
            'dirty_rooms' => Room::where('status', 'dirty')->count(),
            'maintenance_rooms' => Room::where('status', 'maintenance')->count(),
            'today_checkins' => Booking::where('check_in_date', $today)->count(),
            'today_checkouts' => Booking::where('check_out_date', $today)->count(),
            'total_revenue_today' => Booking::whereDate('created_at', $today)->sum('total_amount'),
            'today_bookings'   => Booking::whereDate('created_at', $today)->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'active_bookings'  => Booking::where('status', 'confirmed')->count(),
            'today_guests'     => Guest::whereDate('created_at', $today)->count(),

            // Financial stats (Chart.js)
            'months' => $months,
            'monthly_revenue' => $monthly_revenue,
            'dates' => $dates,
            'daily_revenue' => $daily_revenue,

            // Recent Bookings
            'recentBookings' => Booking::with(['guest', 'room'])
                ->latest()
                ->take(5)
                ->get(),
            'recentPayments' => Payment::with(['booking.guest', 'booking.room'])
                ->latest()
                ->take(5)
                ->get()

        ];

        // Roled based dashboard
        if ($user->hasRole('admin')) {
            return view('dashboard.admin', $data);
        }

        if ($user->hasRole('receptionist')) {
            return view('dashboard.receptionist', $data);
        }
    }

    /**
     * Show user profile
     */
    public function myProfile()
    {
        /** @var User $user */
        $user = Auth::user();

        // Role based profile
        if ($user->hasRole('admin')) {
            return view('profiles.admin', compact('user'));
        } elseif ($user->hasRole('receptionist')) {
            return view('profiles.receptionist', compact('user'));
        } else {
            return view('profiles.housekeeper', compact('user'));
        }
    }

    /**
     * Edit profile form
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('profiles.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        /** @var    User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['name', 'phone']);

        // Checking if a new photo is uploaded and handle the storage
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        $user->update($data);

        LogActivity::log('Profile Update', 'Has updated profile successfully!');

        return redirect()->route('my-profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Change password form
     */
    public function changePassword()
    {
        return view('profiles.change-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Validate current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Change user password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        LogActivity::log('Password Update', 'Has updated password successfully!');

        return redirect()->route('my-profile')
            ->with('success', 'Password updated successfully!');
    }
}
