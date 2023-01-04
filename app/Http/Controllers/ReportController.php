<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * This controller handles the user and admin reports
 */
class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // only the authenticated user can access the reports page and its function
        $this->middleware(['auth']);
    }

    /**
     * Display user and admin report page with the reports data.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if the authenticated user is admin
        if(auth()->user()->is_admin == 1) {
            // Get user registration chart data
            $users = User::select(DB::raw("COUNT(*) as count"))
                        ->whereYear('created_at', date('Y'))
                        ->groupBy(DB::raw("extract(Month From created_at)"))
                        ->pluck('count');

            $months = User::select(DB::raw("extract(Month From created_at) as month"))
                            ->whereYear('created_at', date('Y'))
                            ->groupBy(DB::raw("extract(Month From created_at)"))
                            ->pluck('month');
            
            $datas = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

            foreach($months as $index => $month){
                $datas[$month] = $users[$index];
            }

            // Get advertisement chart data
            $advertisements = Advertisement::select(DB::raw("COUNT(*) as count"))
                        ->whereYear('created_at', date('Y'))
                        ->groupBy(DB::raw("extract(Month From created_at)"))
                        ->pluck('count');

            $advertisement_months = Advertisement::select(DB::raw("extract(Month From created_at) as month"))
                        ->whereYear('created_at', date('Y'))
                        ->groupBy(DB::raw("extract(Month From created_at)"))
                        ->pluck('month');

            $advertisement_datas = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

            foreach($advertisement_months as $index => $advertisement_month){
                $advertisement_datas[$advertisement_month] = $advertisements[$index];
            }

            // display the admin report page with the reports data
            return view('reports.index', compact('datas', 'advertisement_datas'));
        } 

        // Get the bookings data 
        $user = auth()->user();
        $bookings = Booking::where("advertisement_user_id", "=", $user->id)
                            ->where("status", "=", "Approved")->get();

        // Date Range Filter for reports
        // check if the request has from and to data for data filter
        if(request()->has('from_date') && request()->has('to_date')){
            $from_date  = request()->query('from_date');
            $to_date = request()->query('to_date');

            // Filter the data between the two dates
            $bookings = Booking::where("advertisement_user_id", "=", $user->id)
                                ->where("status", "=", "Approved")
                                ->whereBetween("date", [$from_date, $to_date])->get();
        }

        // Search 
        // check if the request has search keyword
        if(request()->has('search')){
            // find the keyword in the bookings data
            $bookings = Booking::where("bookings.advertisement_user_id", "=", $user->id)
                                ->where("bookings.status", "=", "Approved")
                                ->join('advertisements', 'bookings.advertisement_id', '=', 'advertisements.id')
                                ->select('advertisements.*', 'bookings.*')
                                ->where(function($query){
                                    $query->orWhere('bookings.name', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.date', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('advertisements.title', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('advertisements.price', 'iLIKE', '%' . request()->query('search') . '%');
                                })->get();
        }

        // display the users reports page view with the bookings data
        return view('reports.index', compact('bookings'));
    }
}