<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffSchedule extends Model
{
    protected $fillable = ['user_id', 'shift_id', 'shift_date', 'notes'];

    // This schedule belongs to a specific user (staff member)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // This schedule is associated with a specific shift
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
