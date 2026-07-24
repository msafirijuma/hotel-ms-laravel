<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Guest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Generate hotel analytical reports based on selected month and year filters.
     */
    public function index(Request $request)
    {
        // Capture input filter parameters (Default is current month and year)
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        // OCCUPANCY RATE: Calculated based on historical bookings for that specific month/year
        $total_rooms = Room::count() ?: 1;

        $occupied_rooms_count = Booking::whereYear('check_in_date', $year)
            ->whereMonth('check_in_date', $month)
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->distinct('room_id')
            ->count('room_id');

        $occupancy_rate = $occupied_rooms_count > 0 ? round(($occupied_rooms_count / $total_rooms) * 100) : 0;

        // REVENUE FROM PAYMENTS: Filtered strictly by selected month and year parameters
        $month_revenue = Payment::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->where('status', 'paid')
            ->sum('amount_paid');

        // FIXED TOTAL GUESTS: Count unique guests who had valid bookings in that specific period
        $total_guests = Booking::whereYear('check_in_date', $year)
            ->whereMonth('check_in_date', $month)
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->distinct('guest_id')
            ->count('guest_id');

        // AVERAGE STAYING DAYS: Calculated strictly within the selected calendar boundaries
        $avg_stay = Booking::whereYear('check_in_date', $year)
            ->whereMonth('check_in_date', $month)
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->select(DB::raw('AVG(DATEDIFF(check_out_date, check_in_date)) as average_days'))
            ->first()->average_days ?? 0;

        $avg_stay = round($avg_stay, 1);

        // ==========================================
        // DAILY REVENUE CHART: Last 30 Days trend tracker
        // ==========================================
        $daily_payments = Payment::select(
            DB::raw('DATE(payment_date) as date'),
            DB::raw('SUM(amount_paid) as total')
        )
            ->where('payment_date', '>=', now()->subDays(30))
            ->where('status', 'paid')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $daily_labels = [];
        $daily_data = [];
        foreach ($daily_payments as $payment) {
            $daily_labels[] = Carbon::parse($payment->date)->format('d M');
            $daily_data[] = $payment->total;
        }

        // ==========================================
        // MONTHLY REVENUE CHART: Tracked within the selected filter year boundary
        // ==========================================
        $monthly_payments = Payment::select(
            DB::raw('MONTH(payment_date) as month_num'),
            DB::raw('SUM(amount_paid) as total')
        )
            ->whereYear('payment_date', $year)
            ->where('status', 'paid')
            ->groupBy('month_num')
            ->orderBy('month_num', 'ASC')
            ->get()->pluck('total', 'month_num')->toArray();

        $monthly_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthly_data = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthly_data[] = $monthly_payments[$m] ?? 0;
        }

        return view('reports.index', compact(
            'month',
            'year',
            'occupancy_rate',
            'month_revenue',
            'total_guests',
            'avg_stay',
            'daily_labels',
            'daily_data',
            'monthly_labels',
            'monthly_data'
        ));
    }
}