<?php

namespace App\Http\Controllers;

use App\Equipment;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Dashboard/Index',['equipment' => Equipment::query()->get()]);
    }
}
