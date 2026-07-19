<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HousekeepingTask extends Model
{
    protected $fillable = ['room_id', 'assigned_to', 'assigned_by', 'notes', 'status', 'completed_at'];

    // relationship with Room
    public function room() 
    { 
        return $this->belongsTo(Room::class); 
    }

    // relationship with staff (Housekeeper)
    public function housekeeper() 
    { 
        return $this->belongsTo(User::class, 'assigned_to'); 
    }

    // relationship with creator/assigner (Admin/Receptionist)
    public function creator() 
    { 
        return $this->belongsTo(User::class, 'assigned_by'); 
    }
}
