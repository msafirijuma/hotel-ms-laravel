<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'invoice_number',
        'amount_paid',
        'payment_method',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'amount_paid'  => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    // Generate unique invoice number
    public static function generateInvoiceNumber()
    {
        do {
            $number = 'INV-' . strtoupper(uniqid());
        } while (self::where('invoice_number', $number)->exists());

        return $number;
    }
}