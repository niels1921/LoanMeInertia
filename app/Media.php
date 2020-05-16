<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'name',
        'path',
        'model_type',
        'model_id',
        'ext'
    ];
}
