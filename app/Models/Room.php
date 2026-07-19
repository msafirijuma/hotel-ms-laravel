<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number', 'room_type_id', 'status', 'notes', 'floor', 'price_per_night', 'floor'
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id'); 
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function housekeepingTasks()
    {
        return $this->hasMany(HousekeepingTask::class);
    }
}