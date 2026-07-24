<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'reported_by',
        'fixed_by',
        'issue_description',
        'status',
        'fixed_at'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function fixedBy()
    {
        return $this->belongsTo(User::class, 'fixed_by');
    }
}