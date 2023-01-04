<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use willvincent\Rateable\Rateable;

/**
 * This is the Advertisement Model
 */
class Advertisement extends Model
{
    use HasFactory;
    use Rateable;

    // Table Name
    protected $table = 'advertisements';

    // Fillable Attributes
    protected $fillable = ['user_id', 'skilled_trades', 'title', 'description', 'address', 'price', 'image', 'phone'];

    // Primary Key
    protected $primaryKey = 'id';

    // Relationships
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function favourite(){
        return $this->hasMany('App\Models\Favourite');
    }

    public function rating(){
        return $this->hasMany('App\Models\Rating');
    }

    public function booking(){
        return $this->hasMany('App\Models\Booking');
    }
}