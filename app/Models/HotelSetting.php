<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelSetting extends Model
{
    protected $fillable = [
        'hotel_name', 'tagline', 'address', 'phone', 
        'email', 'website', 'tin', 'footer_message', 'logo_path'
    ];
}
