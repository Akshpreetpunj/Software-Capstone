<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use Illuminate\Http\Request;
use Auth;

/**
 * This controller handles the user favourites
 */
class FavouriteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // only the authenticated user can access the favourites page and its function
        $this->middleware(['auth']);
    }

    /**
     * Display the favourites page with the user favourite data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the authenticated user favourite data
        $user = auth()->user();
        $favourites = Favourite::where("user_id", "=", $user->id)->orderBy('id', 'DESC')->paginate(5);

        // Search 
        $search = $request->query('search');

        // check if the request has search keyword
        if($request->has('search')){
            // find the keyword in the favourites data
            $favourites = Favourite::select('advertisements.*', 'favourites.*')
                                ->join('advertisements', 'favourites.advertisement_id', '=', 'advertisements.id')
                                ->where('advertisements.skilled_trades', 'iLIKE', '%' . $search . '%')
                                ->orWhere('advertisements.title', 'iLIKE', '%' . $search . '%')
                                ->orWhere('advertisements.price', 'iLIKE', '%' . $search . '%')
                                ->orWhere('advertisements.created_at', 'iLIKE', '%' . $search . '%')
                                ->orWhere('favourites.created_at', 'iLIKE', '%' . $search . '%')
                                ->orderBy('favourites.id', 'DESC')
                                ->paginate(5);
        }

        // Sort Table
        $sort = $request->query('sort');
        $direction = $request->query('direction');

        // check if the request has sorting field value and direction value
        if($request->has('sort') and $request->has('direction')) {
            // Sort the favourites data 
            $favourites = Favourite::select('advertisements.*', 'favourites.*')
                                    ->join('advertisements', 'favourites.advertisement_id', '=', 'advertisements.id')
                                    ->where("favourites.user_id", "=", $user->id)
                                    ->orderBy($sort, $direction)
                                    ->paginate(5);
        }

        // Category Filter
        $category_filter = $request->query('category_filter');

        // check if the request has category filter value
        if($request->has('category_filter')){
            // Filter the favourites data 
            $favourites = Favourite::select('advertisements.*', 'favourites.*')
                                    ->join('advertisements', 'favourites.advertisement_id', '=', 'advertisements.id')
                                    ->where("favourites.user_id", "=", $user->id)
                                    ->where('skilled_trades', $category_filter)
                                    ->orderBy('favourites.id', 'DESC')
                                    ->paginate(5);
        }

        // display the favourites page view with the favourites data
        return view('favourites.index', compact('user'))->with('favourites', $favourites);
    }

    /**
     * Store the favourite advertisement
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // data validation
        $this->validate($request, array(
            'user_id' => 'required',
            'advertisement_id' => 'required',
        ));

        try {
            $advertisement_id = $request->advertisement_id;
            $status = Favourite::where('user_id', auth()->user()->id)
                      ->where('advertisement_id', $advertisement_id)
                      ->exists();
            
            // check if the advertisement is already in the favourites 
            if($status) {
                // display the message
                return redirect()->back()->with('message', 'This advertisement is already in your favourites!');
            } else {
                // Adding the favourites advertisement to the database
                $favourite = new Favourite();
                $favourite->user_id = $request->user_id;
                $favourite->advertisement_id = $request->advertisement_id;
    
                // save the favourite
                $favourite->save();
                
                // display the success message
                return redirect()->back()->with('success', 'Advertisement added to favourites successfully.');
            }
        } catch(\Exception $exception) {
            // display the error message if there is any exception
            return redirect()->back()->with('error', 'Something went wrong. Please try again!');
        }
    }

    /**
     * Remove the favourite advertisement from the database
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // find the favourite with the help of id
            $favourite = Favourite::where('advertisement_id', $id);
            // delete the favourite
            $favourite->delete();
    
            // display the success message
            return redirect()->back()->with('success', 'Advertisement deleted from favourites successfully.');
        }  catch(\Exception $exception) {
            // display the error message if there is any exception
            return redirect()->back()->with('error', 'Something went wrong. Please try again!');
        }
    }
}