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
        'payment_date'
    ];

    // Uhusiano: Malipo haya ni ya Booking gani
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Uhusiano: Malipo haya yanaweza kuwa na malipo mengi
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
