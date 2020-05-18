<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class Equipment extends Model
{

    protected $fillable = [
        'name',
        'description',
        'address',
        'postal_code',
        'city',
        'country',
        'category_id',
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

    public function addMedia($file){
        if($file->isValid()){
            $title = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $path = Storage::putFile(
                'uploads/'.$this->id, $file
            );
            Media::create([
                'path' => $path,
                'name' => $title,
                'ext' => Str::slug($file->getClientOriginalExtension(),'-'),
                'model_type' => Equipment::class,
                'model_id' => $this->id,
            ]);
        }
    }

    public function getMedia(){
        return Media::where([['model_type',Equipment::class],['model_id',$this->id]])->get();
    }
}
