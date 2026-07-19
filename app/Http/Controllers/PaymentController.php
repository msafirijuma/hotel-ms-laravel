<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Broadcasting\HasBroadcastChannel;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\HotelSetting;
use App\Helpers\LogActivity;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * paymentts index view
     */
    public function index()
    {
        // Pakia malipo yote kuanzia mapya zaidi
        $payments = Payment::with(['booking.guest', 'booking.room.roomType'])
            ->latest()
            ->get();

        return view('payments.index', compact('payments'));
    }

    /**
     * Show create payment form for a specific booking
     */
    public function create($booking_id)
    {
        $booking = Booking::with(['room', 'guest'])->findOrFail($booking_id);

        // Remainign amount
        $total_paid = $booking->payments()->sum('amount_paid');
        $remaining_amount = $booking->total_amount - $total_paid;

        return view('payments.create', compact('booking', 'remaining_amount'));
    }

    /**
     * store payment to database
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount_paid' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,mpesa,tigo_pesa,airtel_money,bank_transfer,card',
        ]);

        // Invoice number (randomly generated)
        $invoice_number = 'INV-' . date('Y') . '-' . strtoupper(Str::random(5));

        $payment = Payment::create([
            'booking_id' => $request->booking_id,
            'invoice_number' => $invoice_number,
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'status' => 'paid',
            'payment_date' => now(),
        ]);

        LogActivity::log('Payment', 'Payment has been done for booking' . $request->booking_id);

        return redirect()->route('payments.invoice', $payment->id)
            ->with('success', 'Payment received successfully!');
    }

    /**
     * show invoice for a specific payment
     */
    public function showInvoice($id)
    {
        $payment = Payment::with(['booking.room.roomType', 'booking.guest'])->findOrFail($id);
        $settings = HotelSetting::first();
        return view('payments.invoice', compact('payment', 'settings'));
    }
}
