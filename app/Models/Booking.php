<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * This is the Booking Model
 */
class Booking extends Model
{
    use HasFactory;

    // Table Name
    protected $table = 'bookings';

    // Primary Key
    protected $primaryKey = 'id';

    // Relationships
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function advertisement(){
        return $this->belongsTo('App\Models\Advertisement');
    }
}
