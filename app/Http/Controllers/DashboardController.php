<?php

namespace App\Http\Controllers;
use App\Equipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use App\loanedequipment;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Index', [
            'filters' => \Illuminate\Support\Facades\Request::all('search', 'trashed'),
            'equipment' => Equipment::query()
                ->orderBy('name')
                ->paginate()
                ->only('id', 'name', 'address', 'postal_code', 'city', 'country'),
        ]);
    }
}


