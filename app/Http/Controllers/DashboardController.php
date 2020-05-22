<?php

namespace App\Http\Controllers;
use App\Equipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use App\loanedequipment;
use App\Media;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Index', [
            'filters' => \Illuminate\Support\Facades\Request::all('search', 'trashed'),
            'equipment' => Equipment::query()
                ->orderBy('created_at', 'desc')
                ->paginate(6)
                ->only('id', 'name','description', 'address', 'postal_code', 'city', 'country'),
             'media' => Media::query(),
        ]);

    }
}


