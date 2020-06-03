<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Media extends Model
{
    protected $fillable = [
        'name',
        'path',
        'url',
        'model_type',
        'model_id',
        'size',
        'priority',
        'ext'
    ];

    public function getUrl(){
        $url = Storage::url($this->path);
        return URL::to('/').$url;
    }
}
