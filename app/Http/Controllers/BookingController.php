<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;

/**
 * This controller handles the appointment booking
 */
class BookingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // only the authenticated user can access the bookings page and its function
        $this->middleware(['auth']);
    }

    /**
     * Display the bookings page with the bookings data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $sort = $request->query('sort');
        $direction = $request->query('direction');

        // Skilled Trade Workers Bookings
        // Get the bookings data for skilled trades worker
        $skilled_trade_bookings = Booking::where("advertisement_user_id", "=", $user->id)->orderBy('id', 'DESC')->paginate(5);

        // Search 
        // check if the request has search keyword
        if($request->has('search')){
            // find the keyword in the bookings data
            $skilled_trade_bookings = Booking::where("bookings.advertisement_user_id", "=", $user->id)
                                ->join('advertisements', 'bookings.advertisement_id', '=', 'advertisements.id')
                                ->join('users', 'bookings.user_id', '=', 'users.id')
                                ->select('advertisements.*', 'bookings.*', 'users.*')
                                ->where(function($query){
                                    $query->orWhere('bookings.name', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.address', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.phone', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('advertisements.title', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.date', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.start_time', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.end_time', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.message', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.status', 'iLIKE', '%' . request()->query('search') . '%');
                                })->orderBy('bookings.id', 'DESC')->paginate(5);
        }

        // Sort Table
        // check if the request has sorting field value and direction value
        if($request->has('sort') and $request->has('direction')){
            // Sort the bookings data 
            $skilled_trade_bookings = Booking::select('users.*', 'advertisements.*', 'bookings.*')
                                ->join('advertisements', 'bookings.advertisement_id', '=', 'advertisements.id')
                                ->join('users', 'bookings.advertisement_user_id', '=', 'users.id')
                                ->where('bookings.advertisement_user_id', '=', $user->id)
                                ->orderBy($sort, $direction)
                                ->paginate(5);
        }

        // Status Filter
        $status_filter = $request->query('status_filter');

        // check if the request has status filter value
        if($request->has('status_filter')){
            // Filter the bookings data  
            $skilled_trade_bookings = Booking::select('users.*', 'advertisements.*', 'bookings.*')
                                ->join('advertisements', 'bookings.advertisement_id', '=', 'advertisements.id')
                                ->join('users', 'bookings.advertisement_user_id', '=', 'users.id')
                                ->where('bookings.advertisement_user_id', '=', $user->id)
                                ->where('bookings.status', $status_filter)
                                ->orderBy('bookings.id', 'DESC')
                                ->paginate(5);
        }
        
        // Clients Bookings
        $client_bookings = Booking::where("user_id", "=", $user->id)->orderBy('id', 'DESC')->paginate(5);

        // Search 
        // check if the request has search keyword
        if($request->has('search')){
            // find the keyword in the bookings data
            $client_bookings = Booking::where("bookings.user_id", "=", $user->id)
                                ->join('advertisements', 'bookings.advertisement_id', '=', 'advertisements.id')
                                ->join('users', 'bookings.user_id', '=', 'users.id')
                                ->select('advertisements.*', 'bookings.*', 'users.*')
                                ->where(function($query){
                                    $query->orWhere('bookings.name', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.address', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.phone', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('advertisements.title', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.date', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.start_time', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.end_time', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.message', 'iLIKE', '%' . request()->query('search') . '%');
                                    $query->orWhere('bookings.status', 'iLIKE', '%' . request()->query('search') . '%');
                                })->orderBy('bookings.id', 'DESC')->paginate(5);
        }

        // Sort Table
        // check if the request has sorting field value and direction value
        if($request->has('sort') and $request->has('direction')){
            // Sort the bookings data 
            $client_bookings = Booking::select('users.*', 'advertisements.*', 'bookings.*')
                                ->join('advertisements', 'bookings.advertisement_id', '=', 'advertisements.id')
                                ->join('users', 'bookings.user_id', '=', 'users.id')
                                ->where('bookings.user_id', '=', $user->id)
                                ->orderBy($sort, $direction)
                                ->paginate(5);
        }

        // Status Filter
        $status_filter = $request->query('status_filter');

        // check if the request has status filter value
        if($request->has('status_filter')){
            // Filter the bookings data  
            $client_bookings = Booking::select('users.*', 'advertisements.*', 'bookings.*')
                                ->join('advertisements', 'bookings.advertisement_id', '=', 'advertisements.id')
                                ->join('users', 'bookings.user_id', '=', 'users.id')
                                ->where('bookings.user_id', '=', $user->id)
                                ->where('bookings.status', $status_filter)
                                ->orderBy('bookings.id', 'DESC')
                                ->paginate(5);
        }

        // display the bookings page view with the bookings data
        return view('bookings.index', compact('client_bookings', 'skilled_trade_bookings'));
    }

    /**
     * Show the form for creating a new booking.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user_id = auth()->user()->id;
        $advertisement_id = $request->input('advertisement_id');
        $advertisement_user_id = Advertisement::find($advertisement_id)->user_id;

        $exists = Booking::where('advertisement_id', $advertisement_id)
                        ->where('user_id', $user_id)->exists();

        // check if there is already a booking created by the user
        if($exists) {
            // display the message
            return redirect()->back()->with('error', 'There is already one appointment booking on this advertisement.');
        } else {
            // check if the user is trying to book an appointment on the advertisement they posted  
            if($user_id == $advertisement_user_id){
                // display the error message
                return redirect()->back()->with('error', 'Appointment cannot be booked on your posted advertisement.');
            } else {
                // display the bookings create page
                return view('bookings.create')->with('advertisement_id', $advertisement_id);
            }
        }
    }

    /**
     * Store a newly created booking in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // data validation
        $request->validate([
            'name' => 'required|string|min:1|max:100',
            'address' => 'required|min:8|max:50',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max::15|nullable',
            'date' => 'required|date|date_format:Y-m-d|after:today',
            'start_time' => 'required|nullable',
            'end_time' => 'required|nullable',
        ]);

        try {
            // Store the booking data in the database
            $bookings = new Booking;

            $bookings->user_id = auth()->user()->id;
            $bookings->advertisement_id = $request->input('advertisement_id');
            $bookings->advertisement_user_id = $request->input('advertisement_user_id');
            $bookings->name = $request->input('name');
            $bookings->address = $request->input('address');
            $bookings->phone = $request->input('phone');
            $bookings->date = $request->input('date');
            $bookings->start_time = $request->input('start_time');
            $bookings->end_time = $request->input('end_time');
            $bookings->message = $request->input('message');
            $bookings->status = 'In progress';
            
            // save the booking
            $bookings->save();
    
            // display the success message
            return redirect('/my-bookings')->with('success', 'Appointment booked successfully.');    
        } catch(\Exception $exception) {
            // display the error message if there is any exception
            return redirect('/my-bookings')->with('error', 'Something went wrong. Please try again!')->withInput();
        }
    }

    /**
     * Remove the specified booking from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // find the booking with the help of id
            $booking = Booking::find($id);
            // delete the booking
            $booking->delete();

            // display the success message
            return redirect()->back()->with('success', 'Appointment booking deleted successfully.');
        } catch(\Exception $exception) {
            // display the error message if there is any exception
            return redirect()->back()->with('error', 'Something went wrong. Please try again!');
        }
    }

    /**
     * Approve the Appointment Booking.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $booking = Booking::find($id);
        // change the booking status to approved
        $booking->status='Approved';
        $booking->save();

        // display the success message
        return redirect()->back()->with('success', 'Appointment booking approved successfully.');
    }

    /**
     * Cancel the Appointment Booking.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $booking = Booking::find($id);
        // change the booking status to cancelled
        $booking->status='Cancelled';
        $booking->save();

        // display the success message
        return redirect()->back()->with('success', 'Appointment booking cancelled successfully.');
    }
}