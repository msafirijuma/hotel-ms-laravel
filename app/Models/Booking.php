<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id', 'room_id', 'booking_code', 'check_in_date', 'check_out_date',
        'number_of_guests', 'total_amount', 'status', 'special_requests'
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id'); 
    }


    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function hotelSetting()
    {
        return $this->belongsTo(HotelSetting::class);
    }

    // Booked by 'staff' user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}