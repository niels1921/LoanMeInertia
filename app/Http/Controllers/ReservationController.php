<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Mockery\Exception;

class ReservationController extends Controller
{
    public function index()
    {
        return Inertia::render('Reservation/Index', [
            'filters' => \Illuminate\Support\Facades\Request::all('search', 'trashed'),
            'reservations' => Auth::user()->account->reservations()->whereDate('from', '>=', Carbon::now())->with('equipment')
                ->paginate()->map(function ($reservation) {
                    return [
                        'id' => $reservation->id,
                        'name'=> $reservation->equipment->name,
                        'equipment_id' => $reservation->equipment->id,
                        'from' => $reservation->from->format('Y-m-d'),
                        'till' => $reservation->till->format('Y-m-d'),
                    ];
                })
        ]);
    }

    public function create(Equipment $equipment)
    {
        return Inertia::render('Reservation/Create',[
            'equipment' => $equipment,
            'disabledDates' => $equipment->getDisabledDates()
        ]);
    }



    public function store(Request $request)
    {
        try {
            $date = json_decode($request->get('date'));
            $start = Carbon::parse($date->start)->addHours(2);
            $end = Carbon::parse($date->end)->addHours(2);
            $array = [
                'from' => $start->toDate(),
                'till' => $end->toDate(),
                'equipment_id' => $request->get('id'),
            ];
            $validator = Validator::make($array, [
                'from' => 'required',
                'till' => 'required',
                'equipment_id' => 'required',
            ]);
            if ($validator->fails()) {
                return Redirect::route('dashboard')->with('error', 'Something went wrong.');
            } else{
                Auth::user()->account->reservations()->create($array);
                return Redirect::route('dashboard')->with('success', 'Reservation created.');
            }
        } catch (Exception $e){
            return Redirect::route('dashboard')->with('error', 'Something went wrong.');
        }
    }

    public function destroy(Request $request)
    {
        $owner = boolval($request->get('owner'));
        $id = $request->get('id');
        $reservation = Auth::user()->account->reservations()->find($id);
        $message = 'Something went wrong.';
        $type = 'error';
        if (!is_null($reservation)) {
            try {
                Reservation::destroy($id);
                $type = 'success';
                $message = 'Reservation successfully cancelled.';
            } catch (Exception $e){}
        }
        return $this->redirectDestroy($type,$message,$owner);
    }

    private function redirectDestroy($type, $message, $owner = false){
        switch ($owner){
            case true:
                return Redirect::back()->with($type, $message);
                break;
            default:
                return Redirect::route('reservations')->with($type, $message);
                break;
        }
    }

    public function getDates($id){
        $equipment = Equipment::find($id);
        return response($equipment->reservations()->whereDate('from', '>=', Carbon::now())->get(),200);
    }
}
