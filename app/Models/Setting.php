<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * This is the Setting Model
 */
class Setting extends Model
{
    use HasFactory;

    // Table Name
    protected $table = 'settings';

    // Primary Key
    protected $primaryKey = 'id';
}
