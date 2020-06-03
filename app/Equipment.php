<?php

namespace App;

use Carbon\Carbon;
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

    public function media(){
        return $this->hasMany('App\Media', 'model_id')->orderBy('priority');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class,'equipment_id');
    }

    function getJson(){
        $item = $this->toArray();
        $item['image'] =  $this->getFeatured();
        return $item;
    }

    public function addMedia($file, $priority = 1000){
        if($file->isValid()){
            $title = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $path = Storage::putFile(
                'public/uploads/'.$this->id, $file
            );
            $url =  URL::to('/').Storage::url($path);
            Media::create([
                'url' => $url,
                'path' => $path,
                'name' => $title,
                'priority' => $priority,
                'size' => $file->getSize(),
                'ext' => Str::slug($file->getClientOriginalExtension(),'-'),
                'model_type' => Equipment::class,
                'model_id' => $this->id,
            ]);
        }
    }

    public function updateMedia($file, $priority){
        $item = $this->media()->find($file->id);
        if(!is_null($item)){
            $item->update(['priority' => $priority]);
        }
    }

    public function getMedia(){
        return Media::where([['model_type',Equipment::class],['model_id',$this->id]]);
    }

    public function getFeatured(){
        if ($this->getMedia()->first()){
            return $this->getMedia()->first()->getUrl();
        }
        return null;
    }

    public function getDisabledDates(){
        return $this->reservations()->whereDate('from', '>=', Carbon::now())->get()->map(function ($reservation) {
            return [
                'start' => [
                    'day' => $reservation->from->format('d'),
                    'month' => $reservation->from->modify('-1 months')->format('m'),
                    'year' => $reservation->from->format('Y'),
                ],
                'end' => [
                    'day' => $reservation->till->format('d'),
                    'month' => $reservation->till->modify('-1 months')->format('m'),
                    'year' => $reservation->till->format('Y'),
                ],
            ];
        });
    }
}
