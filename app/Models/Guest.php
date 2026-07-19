<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name', 'email', 'phone', 'id_number', 'address', 'country'
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}