<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'equipment_id',
        'postal_code',
        'from',
        'till',
    ];
}
