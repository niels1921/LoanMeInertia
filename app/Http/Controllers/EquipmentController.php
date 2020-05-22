<?php

namespace App\Http\Controllers;

use App\Category;
use App\Equipment;
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

    public function userEquipment(){
        return Inertia::render('Equipment/Index', [
            'filters' => \Illuminate\Support\Facades\Request::all('search', 'trashed'),
            'equipment' => Equipment::query()
                ->orderBy('name')
                ->paginate()
                ->only('id', 'name', 'address', 'postal_code', 'city','country'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Equipment/Create',[
            'categories' => Category::all()->toArray()
        ]);    }

    public function store()
    {
//        $files = Request::all()['files'];
//
//        dd($files);

        $equipment = Auth::user()->account->equipment()->create(
            Request::validate([
                'name' => ['required', 'max:100'],
                'description' => [],
//                'category' => ['required'],
                'address' => ['required', 'max:150'],
                'postal_code' => ['required', 'max:25'],
                'city' => ['required', 'max:50'],
                'country' => ['required', 'max:60'],
            ])
        );

        if(Request::has('files')){
            $files = Request::all()['files'];
            foreach ($files as $file){
                $equipment->addMedia($file);
            }
        }


        return Redirect::route('equipment')->with('success', 'Equipment created.');
    }

    public function edit(Equipment $equipment)
    {
        dd($equipment->getJson());
        return Inertia::render('Equipment/Edit', [
            'categories' => Category::all()->toArray(),
            'equipment' => [
                'id' => $equipment->id,
                'name' => $equipment->name,
                'category' => $equipment->category,
                'address' => $equipment->address,
                'postal_code' => $equipment->postal_code,
                'city' => $equipment->city,
                'country' => $equipment->country,
            ],
        ]);
    }

    public function update(Equipment $equipment)
    {
        $equipment->update(
            Request::validate([
                'name' => ['required', 'max:100'],
                'description' => [],
                'category_id' => ['required'],
                'address' => ['required', 'max:150'],
                'postal_code' => ['required', 'max:25'],
                'city' => ['required', 'max:50'],
                'country' => ['required', 'max:60'],
            ])
        );

        return Redirect::route('equipment')->with('success', 'Equipment updated.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return Redirect::route('equipment')->with('success', 'Equipment deleted.');
    }
}
