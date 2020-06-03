<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $dates = ['from', 'till'];

    protected $fillable = [
        'user_id',
        'equipment_id',
        'from',
        'till',
    ];

    public function equipment()
    {
        return $this->hasOne(Equipment::class, 'id', 'equipment_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
