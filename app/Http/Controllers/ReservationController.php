<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ReservationController extends Controller
{
    public function index()
    {
        return Inertia::render('Equipment/Index', [
            'filters' => \Illuminate\Support\Facades\Request::all('search', 'trashed'),
            'reservation' => Reservations::query()
        ]);
    }

    public function create()
    {
        return Inertia::render('Reservation/Create');
    }

    public function store()
    {
        Auth::user()->account->reservation()->create(
            Request::validate([
                'id' => [required],
                'user_id'=> [required],
                'equipment_id' => [required],
                'from' => [required],
                'till' => [required]
            ])
        );
        return Redirect::route('dashboard')->with('success', 'Reservation created.');
    }
}
