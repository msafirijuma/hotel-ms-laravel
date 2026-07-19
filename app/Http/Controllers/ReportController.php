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
    public function index(Request $request)
    {
        // Get month and year (Default is current month and year)
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        // Occupancy Rate
        $total_rooms = Room::count() ?: 1;
        $occupied_rooms = Room::where('status', 'occupied')->count();
        $occupancy_rate = round(($occupied_rooms / $total_rooms) * 100);

        // Month Revenue from Payments (Selected month)
        $month_revenue = Payment::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->where('status', 'paid')
            ->sum('amount_paid');

        // Total number of guests (Available or even once booked)
        $total_guests = Guest::count();

        // Average Staying Days
        $avg_stay = Booking::select(DB::raw('AVG(DATEDIFF(check_out_date, check_in_date)) as average_days'))
            ->first()->average_days ?? 0;

        // ==========================================
        // CHART: Last 30 days
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
        // CHART: Total payment for all months (Selected month)
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
            'month', 'year', 'occupancy_rate', 'month_revenue', 
            'total_guests', 'avg_stay', 'daily_labels', 'daily_data', 
            'monthly_labels', 'monthly_data'
        ));
    }
}
