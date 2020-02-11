<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Equipment extends Model
{

    protected $fillable = [
        'name',
        'description',
        'address',
        'postal_code',
        'city',
        'country'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    function getJson(){
        $item = $this->toArray();
        $item['image'] =  URL::to('/').$this->getMedia('gallery')[0]->getUrl();
        return $item;
    }
}
