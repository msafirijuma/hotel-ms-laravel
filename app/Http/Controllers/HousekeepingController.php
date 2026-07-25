<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\User;
use App\Models\HousekeepingTask;
use App\Models\StaffSchedule;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HousekeepingController extends Controller
{
    /**
     * Calculate current shift
     */
    private function getCurrentShift()
    {
        $currentHour = (int)date('H');
        return ($currentHour >= 6 && $currentHour < 14 ? 'morning' : ($currentHour >= 14 && $currentHour < 22 ? 'afternoon' : 'night'));
    }

    /**
     * Main index (root view)
     */
    public function index()
    {
        /** @var User $user */
        $user = auth::user();
        $is_admin = $user->hasRole('admin') || $user->role === 'admin';
        $today = \Carbon\Carbon::today()->format('Y-m-d');

        // Housekeeping main query
        $query = HousekeepingTask::with(['room.roomType', 'creator'])
            ->whereIn('status', ['pending', 'in_progress', 'completed']);

        if ($is_admin) {
            // Admin controls everything 
            // Using select and join to get shift of today's shift
            $tasks = $query->select('housekeeping_tasks.*', 'shifts.name as shift_name')
                ->leftJoin('staff_schedules as ss', function ($join) use ($today) {
                    $join->on('housekeeping_tasks.assigned_to', '=', 'ss.user_id')
                        ->whereDate('ss.shift_date', $today);
                })
                ->leftJoin('shifts', 'ss.shift_id', '=', 'shifts.id')
                ->latest('housekeeping_tasks.created_at')
                ->get();

            $dirty_rooms = Room::where('status', 'dirty')->orderBy('room_number')->get();
            $hk_staff = User::whereHas('roles', function ($q) {
                $q->where('name', 'housekeeping');
            })->orderBy('name')->get();
        } else {
            // Housekeeper tasks only (only self task)
            $tasks = $query->select('housekeeping_tasks.*', 'shifts.name as shift_name')
                ->leftJoin('staff_schedules as ss', function ($join) use ($today) {
                    $join->on('housekeeping_tasks.assigned_to', '=', 'ss.user_id')
                        ->whereDate('ss.shift_date', $today);
                })
                ->leftJoin('shifts', 'ss.shift_id', '=', 'shifts.id')
                ->where('housekeeping_tasks.assigned_to', $user->id)
                ->latest('housekeeping_tasks.created_at')
                ->get();

            $dirty_rooms = collect();
            $hk_staff = collect();
        }

        return view('housekeeping.index', compact('tasks', 'dirty_rooms', 'hk_staff', 'is_admin'));
    }


    /**
     * Assign view
     */
    public function assign()
    {
        $current_shift = $this->getCurrentShift();
        $today = \Carbon\Carbon::today()->format('Y-m-d');

        // dirty room only
        $dirty_rooms = Room::with('roomType')->where('status', 'dirty')->orderBy('room_number')->get();

        // All housekepers with today's task
        $housekeepers = User::select('users.id', 'users.name', 'shifts.name as shift')
            ->selectRaw("COUNT(CASE WHEN ht.status = 'pending' AND DATE(ht.created_at) = CURDATE() THEN 1 END) as pending_tasks")
            ->selectRaw("COUNT(CASE WHEN ht.status = 'in_progress' AND DATE(ht.created_at) = CURDATE() THEN 1 END) as in_progress")
            ->join('staff_schedules as ss', function ($join) use ($today) {
                $join->on('users.id', '=', 'ss.user_id')
                    ->whereDate('ss.shift_date', $today);
            })
            ->join('shifts', 'ss.shift_id', '=', 'shifts.id')
            ->leftJoin('housekeeping_tasks as ht', 'users.id', '=', 'ht.assigned_to')
            ->groupBy('users.id', 'users.name', 'shifts.name')
            ->orderByRaw("CASE WHEN LOWER(shifts.name) = ? THEN 1 ELSE 2 END ASC", [strtolower($current_shift)])
            ->orderByRaw("(COUNT(CASE WHEN ht.status = 'pending' AND DATE(ht.created_at) = CURDATE() THEN 1 END) + COUNT(CASE WHEN ht.status = 'in_progress' AND DATE(ht.created_at) = CURDATE() THEN 1 END)) ASC")
            ->orderBy('users.name', 'asc')
            ->get();

        return view('housekeeping.assign', compact('dirty_rooms', 'housekeepers', 'current_shift'));
    }

    /**
     * Manual assign logic
     */
    public function assignManual(Request $request)
    {
        $request->validate([
            'room_id'     => 'required|exists:rooms,id',
            'assigned_to' => 'required|exists:users,id',
            'description'       => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            HousekeepingTask::create([
                'room_id'     => $request->room_id,
                'assigned_to' => $request->assigned_to,
                'assigned_by' => Auth::id(),
                'description'       => trim($request->description),
                'status'      => 'pending'
            ]);

            Room::where('id', $request->room_id)->update(['status' => 'cleaning']);

            LogActivity::log('Housekeeping', 'Has manually assign room no. ' . $request->room_id .  ' to housekeeper.');
        });

        // Back to root view (housekeeping)
        return redirect()->route('housekeeping.index')->with('success', 'Task assigned successfully!');
    }

    /**
     * Auto-Assign logic
     */
    public function assignAuto(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $current_shift = $this->getCurrentShift();
        $today = \Carbon\Carbon::today()->format('Y-m-d');

        $least_loaded = User::select('users.id', 'users.name')
            ->selectRaw("COUNT(ht.id) as total_tasks")
            ->join('staff_schedules as ss', function ($join) use ($today) {
                $join->on('users.id', '=', 'ss.user_id')
                    ->whereDate('ss.shift_date', $today);
            })
            ->join('shifts', 'ss.shift_id', '=', 'shifts.id')
            ->leftJoin('housekeeping_tasks as ht', function ($join) {
                $join->on('users.id', '=', 'ht.assigned_to')
                    ->whereIn('ht.status', ['pending', 'in_progress'])
                    ->whereDate('ht.created_at', DB::raw('CURDATE()'));
            })
            ->groupBy('users.id', 'users.name', 'shifts.name')
            ->orderByRaw("CASE WHEN LOWER(shifts.name) = ? THEN 1 ELSE 2 END ASC", [strtolower($current_shift)])
            ->orderBy('total_tasks', 'asc')
            ->orderBy('users.name', 'asc')
            ->first();

        if ($least_loaded) {
            DB::transaction(function () use ($request, $least_loaded) {
                HousekeepingTask::create([
                    'room_id'     => $request->room_id,
                    'assigned_to' => $least_loaded->id,
                    'assigned_by' => Auth::id(),
                    'description'       => "Auto-assigned (least loaded staff)",
                    'status'      => 'pending'
                ]);

                Room::where('id', $request->room_id)->update(['status' => 'cleaning']);

                LogActivity::log('Housekeeping', 'System has auto assigned this room to ' . $least_loaded->name);
            });

            // Back to root view (housekeeping)
            return redirect()->route('housekeeping.index')->with('success', "Task auto assigned to {$least_loaded->name}!");
        }

        return back()->with('error', 'No housekeeper on schedule found for today.');
    }

    /**
     * Start cleaning Process
     */
    public function startCleaning(HousekeepingTask $task)
    {
        $task->update(['status' => 'in_progress']);
        $task->room->update(['status' => 'cleaning']);

        LogActivity::log('Housekeeping', 'Started cleaning room No. ' . $task->room->room_number);

        return back()->with('success', 'Task started successfully!');
    }

    /**
     * Complete cleaning room
     */
    public function completeCleaning(HousekeepingTask $task)
    {
        $task->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        $task->room->update(['status' => 'available']);

        LogActivity::log('Housekeeping', 'Finished cleaning room No. ' . $task->room->room_number);

        return back()->with('success', 'Room cleaned successfully and available for booking!');
    }


    // ************************* Housekeeping **************************
    /**
     * Housekeeepr self schedule
     */
    public function mySchedule()
    {
        $schedules = StaffSchedule::with('shift')
            ->where('user_id', Auth::id())
            ->whereDate('shift_date', '>=', \Carbon\Carbon::today())
            ->orderBy('shift_date', 'asc')
            ->get();

        return view('housekeeping.my-schedule', compact('schedules'));
    }

    /**
     * Tasks History
     */
    public function cleaningHistory()
    {
        $tasks = HousekeepingTask::with('room.roomType')
            ->where('assigned_to', Auth::id())
            ->where('status', 'completed')
            ->latest('completed_at')
            ->get();

        return view('housekeeping.history', compact('tasks'));
    }

    /**
     * All Dirty Rooms View
     */
    public function dirtyRooms()
    {
        $rooms = Room::with('roomType')
            ->where('status', 'dirty')
            ->orderBy('room_number')
            ->get();

        return view('housekeeping.dirty-rooms', compact('rooms'));
    }
}
