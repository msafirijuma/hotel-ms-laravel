<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price_per_night', 'max_occupancy', 'image', 'max_adults', 'max_children' 
        ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function images()
    {
        // Aina moja ya chumba ina picha nyingi za gallery
        return $this->hasMany(RoomTypeImage::class, 'room_type_id')->orderBy('is_primary', 'desc');
    }
}