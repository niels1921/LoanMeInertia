<?php

namespace App\Http\Controllers;

use App\Category;
use App\Equipment;
use App\Media;
use App\Organization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

use Inertia\Inertia;

class EquipmentController extends Controller
{
    public function index()
    {
        return Inertia::render('Equipment/Index', [
            'filters' => \Illuminate\Support\Facades\Request::all('search', 'trashed'),
            'equipment' => Auth::user()->account->equipment()
                ->orderBy('name')
                ->paginate()
                ->only('id', 'name', 'address', 'postal_code', 'city','country'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Equipment/Create',[
            'categories' => Category::all()->toArray()
        ]);
    }

    public function store()
    {
       $equipment = Auth::user()->account->equipment()->create(
            Request::validate([
                'name' => ['required', 'max:100'],
                'description' => ['max:150'],
                'category_id' => ['required'],
                'address' => ['required', 'max:150'],
                'postal_code' => ['required', 'max:25'],
                'city' => ['required', 'max:50'],
                'country' => ['required', 'max:60'],
            ])
        );

        if(Request::has('files')){
            $files = Request::all()['files'];
            foreach ($files as $key => $file){
                $equipment->addMedia($file,$key);
            }
        }
        return Redirect::route('equipment')->with('success', 'Equipment created.');
    }

    public function edit(Equipment $equipment)
    {
        return Inertia::render('Equipment/Edit', [
            'equipment' => [
                'id' => $equipment->id,
                'name' => $equipment->name,
                'category_id' => $equipment->category_id,
                'address' => $equipment->address,
                'postal_code' => $equipment->postal_code,
                'city' => $equipment->city,
                'country' => $equipment->country,
                'files' => $equipment->media()->orderBy('priority')->get(),
            ],
            'categories' => Category::all()->toArray(),
            'reservations' => $equipment->reservations()->whereDate('from', '>=', Carbon::now())->with('equipment')
                ->paginate()->map(function ($reservation) {
                    return [
                        'id' => $reservation->id,
                        'name'=> $reservation->user->name,
                        'equipment_id' => $reservation->equipment->id,
                        'from' => $reservation->from->format('Y-m-d'),
                        'till' => $reservation->till->format('Y-m-d'),
                    ];
                })
        ]);
    }

    public function update()
    {
        $equipment = Equipment::find(Request::get('id'));
        $equipment->update(
            Request::validate([
                'name' => ['required', 'max:100'],
                'description' => ['max:150'],
                'category_id' => ['required'],
                'address' => ['required', 'max:150'],
                'postal_code' => ['required', 'max:25'],
                'city' => ['required', 'max:50'],
                'country' => ['required', 'max:60'],
            ])
        );
        if(Request::has('files')){
            $files = Request::all()['files'];
            foreach ($files as $key => $file){
                if(gettype($file) == "string"){
                    $file = json_decode($file);
                    $equipment->updateMedia($file,$key);
                } else {
                    $equipment->addMedia($file,$key);
                }
            }
        }
        if(Request::has('removedMedia')) {
            if(gettype(Request::get('removedMedia')) == "string"){
                $removedMedia = json_decode(Request::get('removedMedia'));
                Media::destroy($removedMedia);
            }
        }
        return Redirect::back()->with('success', 'Equipment updated.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return Redirect::back()->with('success', 'Organization deleted.');
    }

    public function restore(Organization $organization)
    {
        $organization->restore();
        return Redirect::back()->with('success', 'Organization restored.');
    }
}
