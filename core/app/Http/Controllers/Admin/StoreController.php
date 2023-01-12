<?php

namespace App\Http\Controllers\Admin;

use App\Lib\BusLayout;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StoreController extends Controller
{
    //
    protected $depoRelations = ['user', 'gateway'];
    
    public function users()
    {
        $pageTitle = 'Users';
        $emptyMessage = 'No user found';
        $users = \App\Models\User::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.store.users', compact('pageTitle', 'emptyMessage', 'users'));
    }

    public function ticket()
    {
        $pageTitle = "Trips";
        $emptyMessage = "No trip found";
        
        $stoppages = \App\Models\Counter::where('status', 1)->get();

        $trips = \App\Models\Trip::with(['fleetType' ,'route', 'schedule', 'startFrom' , 'endTo'])->where('status', 1)->paginate(getPaginate(10));

        return view('admin.store.trips', compact('pageTitle', 'emptyMessage', 'stoppages', 'trips'));
    }

    public function ticketSearch(Request $request)
    {
        if($request->pickup && $request->destination && $request->pickup == $request->destination){
            $notify[] = ['error', 'Please select pickup point and destination point properly'];
            return redirect()->back()->withNotify($notify);
        }
        if($request->date_of_journey && Carbon::parse($request->date_of_journey)->format('Y-m-d') < Carbon::now()->format('Y-m-d')){
            $notify[] = ['error', 'Date of journey can\'t be less than today.'];
            return redirect()->back()->withNotify($notify);
        }

        $trips = \App\Models\Trip::active();

        if($request->pickup && $request->destination){
            Session::flash('pickup', $request->pickup);
            Session::flash('destination', $request->destination);

            $pickup = $request->pickup;
            $destination = $request->destination;
            $trips = $trips->with('route')->get();
            $tripArray = array();

            foreach ($trips as $trip) {
                $startPoint = array_search($trip->start_from , array_values($trip->route->stoppages));
                $endPoint = array_search($trip->end_to , array_values($trip->route->stoppages));
                $pickup_point = array_search($pickup , array_values($trip->route->stoppages));
                $destination_point = array_search($destination , array_values($trip->route->stoppages));
                if($startPoint < $endPoint){
                    if($pickup_point >= $startPoint && $pickup_point < $endPoint && $destination_point > $startPoint && $destination_point <= $endPoint){
                        array_push($tripArray, $trip->id);
                    }
                }else{
                    $revArray = array_reverse($trip->route->stoppages);
                    $startPoint = array_search($trip->start_from ,array_values($revArray));
                    $endPoint = array_search($trip->end_to ,array_values($revArray));
                    $pickup_point = array_search($pickup ,array_values($revArray));
                    $destination_point = array_search($destination ,array_values($revArray));
                    if($pickup_point >= $startPoint && $pickup_point < $endPoint && $destination_point > $startPoint && $destination_point <= $endPoint){
                        array_push($tripArray, $trip->id);
                    }
                }
            }

            $trips = \App\Models\Trip::active()->whereIn('id',$tripArray);
        }else{
            if($request->pickup){
                Session::flash('pickup', $request->pickup);
                $pickup = $request->pickup;
                $trips = $trips->whereHas('route' , function($route) use ($pickup){
                    $route->whereJsonContains('stoppages' , $pickup);
                });
            }

            if($request->destination){
                Session::flash('destination', $request->destination);
                $destination = $request->destination;
                $trips = $trips->whereHas('route' , function($route) use ($destination){
                    $route->whereJsonContains('stoppages' , $destination);
                });
            }
        }

        $stoppages = \App\Models\Counter::where('status', 1)->get();
        $trips = $trips->with( ['fleetType' ,'route', 'schedule', 'startFrom' , 'endTo'] )->where('status', 1)->paginate(getPaginate());

        $pageTitle = 'Search Result';
        $emptyMessage = 'There is no trip available';
        $fleetType = \App\Models\FleetType::active()->get();
        $schedules = \App\Models\Schedule::all();
        $routes = \App\Models\VehicleRoute::active()->get();

        return view('admin.store.trips', compact('pageTitle', 'emptyMessage', 'stoppages', 'trips'));
    }

    public function showSeat($id){
        $trip = \App\Models\Trip::with( ['fleetType' ,'route', 'schedule', 'startFrom' , 'endTo', 'assignedVehicle.vehicle', 'bookedTickets'])->where('status', 1)->where('id', $id)->firstOrFail();
        $pageTitle = $trip->title;
        $route     = $trip->route;
        $stoppageArr = $trip->route->stoppages;
        $stoppages = \App\Models\Counter::routeStoppages($stoppageArr);
        $busLayout = new BusLayout($trip);
        
        return view('admin.store.book_ticket', compact('pageTitle','trip' , 'stoppages','busLayout',));
    }

    public function bookings()
    {
        $pageTitle = 'All Ticket';
        $emptyMessage = 'There is no ticket found';
        $tickets = \App\Models\BookedTicket::with(['trip', 'pickup', 'drop', 'user'])->paginate(getPaginate());
        return view('admin.ticket.log', compact('pageTitle', 'emptyMessage', 'tickets'));
    }

    public function payments()
    {
        $pageTitle = 'All Payment';
        $emptyMessage = 'There is no payment';
        $deposits = \App\Models\Deposit::where('status','!=',0)->with($this->depoRelations)->paginate(getPaginate());
        return view('admin.deposit.log', compact('pageTitle', 'emptyMessage', 'deposits'));
    }
}
