<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['booking.guest', 'booking.room'])
            ->latest()
            ->paginate(15);

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $booking = null;

        if ($request->filled('booking_id')) {
            $booking = Booking::with(['guest', 'room', 'payments'])
                ->findOrFail($request->booking_id);
        }

        $bookings = Booking::with('guest')
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->latest()
            ->get();

        return view('payments.create', compact('booking', 'bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id'     => 'required|exists:bookings,id',
            'amount_paid'    => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,mpesa,tigo_pesa,airtel_money,bank_transfer,card',
            'status'         => 'required|in:pending,paid,refunded',
            'payment_date'   => 'nullable|date',
        ]);

        $booking = Booking::with('payments')->findOrFail($validated['booking_id']);

        $totalPaid = $booking->payments->where('status', 'paid')->sum('amount_paid');
        $balance   = $booking->total_amount - $totalPaid;

        if ($validated['status'] === 'paid' && $validated['amount_paid'] > $balance) {
            return back()
                ->withInput()
                ->with('error', 'Amount exceeds remaining balance of TZS ' . number_format($balance, 2));
        }

        $payment = Payment::create([
            'booking_id'     => $validated['booking_id'],
            'invoice_number' => Payment::generateInvoiceNumber(),
            'amount_paid'    => $validated['amount_paid'],
            'payment_method' => $validated['payment_method'],
            'status'         => $validated['status'],
            'payment_date'   => $validated['payment_date'] ?? now(),
        ]);

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment recorded successfully. Invoice: ' . $payment->invoice_number);
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.guest', 'booking.room']);
        return view('payments.show', compact('payment'));
    }

    /**
     * show invoice for a specific payment
     */
    // public function showInvoice($id)
    // {
    //     $payment = Payment::with(['booking.room.roomType', 'booking.guest'])->findOrFail($id);
    //     $settings = HotelSetting::first();
    //     return view('payments.invoice', compact('payment', 'settings'));
    // }

    public function invoice(Payment $payment)
    {
        $payment->load(['booking.guest', 'booking.room.roomType', 'booking.payments']);
        return view('payments.invoice', compact('payment'));
    }
}