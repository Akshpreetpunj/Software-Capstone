<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * This is the Rating Model
 */
class Rating extends Model
{
    use HasFactory;

    // Table Name
    protected $table = 'ratings';

    // Fillable Attributes
    protected $fillable = ['rating', 'rateable_id', 'user_id'];

    // Primary Key
    protected $primaryKey = 'id';

    // Relationships
    public function rateable(){
        return $this->morphTo();
    }
    
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function advertisement(){
        return $this->belongsTo('App\Models\Advertisement');
    }
}