<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use App\Models\Favourite;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use AppRating;
use Illuminate\Support\Facades\Cookie;

/**
 * This controller handles all the advertisement
 */
class TradeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // both the guest and authenticated user can access the trade page but only the authenticated 
        // user can access its function fully
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display the trade page with all the advertisement.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get all the advertisement data 
        $advertisements = Advertisement::orderBy('id', 'DESC')->paginate(5);

        // Search 
        $search = $request->query('search');

        // check if the request has search keyword
        if($request->has('search')){
            // find the keyword in the advertisement data
            $advertisements = Advertisement::where('title', 'iLIKE', '%' . $search . '%')
                                        ->orWhere('description', 'iLIKE', '%' . $search . '%')
                                        ->orWhere('price', 'iLIKE', '%' . $search . '%')
                                        ->orWhere('image', 'iLIKE', '%' . $search . '%')
                                        ->orderBy('id', 'DESC')
                                        ->paginate(5);
        }

        // Category Filter
        $category_filter = $request->query('category_filter');

        // check if the request has category filter value
        if($request->has('category_filter')){
            // Filter the advertisement data
            $advertisements = Advertisement::where('skilled_trades', $category_filter)
                                        ->orderBy('id', 'DESC')
                                        ->paginate(5);
        }

        // Sort Filter
        $sortby = $request->query('sortby');

        // check if the request has sort filter value
        if($request->has('sortby')){
            // Sort the advertisement data
            if($sortby == 'latest'){
                $advertisements = Advertisement::orderBy('created_at', 'DESC')->paginate(5);
            }
            if($sortby == 'oldest'){
                $advertisements = Advertisement::orderBy('created_at', 'ASC')->paginate(5);
            }
            if($sortby == 'title_asc'){
                $advertisements = Advertisement::orderBy('title', 'ASC')->paginate(5);
            }
            if($sortby == 'title_desc'){
                $advertisements = Advertisement::orderBy('title', 'DESC')->paginate(5);
            }
            if($sortby == 'rating_asc'){
                $advertisements = Advertisement::select('advertisements.*')
                                    ->leftJoin('ratings', 'advertisements.id', '=', 'ratings.rateable_id')
                                    ->addSelect(DB::raw('AVG(COALESCE(ratings.rating, 0)) as average_rating'))
                                    ->groupBy('advertisements.id')
                                    ->orderBy('average_rating', 'ASC')
                                    ->paginate(5);
            }
            if($sortby == 'rating_desc'){
                $advertisements = Advertisement::select('advertisements.*')
                                    ->leftJoin('ratings', 'advertisements.id', '=', 'ratings.rateable_id')
                                    ->addSelect(DB::raw('AVG(COALESCE(ratings.rating, 0)) as average_rating'))
                                    ->groupBy('advertisements.id')
                                    ->orderBy('average_rating', 'DESC')
                                    ->paginate(5);
            }
            if($sortby == 'price_asc'){
                $advertisements = Advertisement::orderBy('price', 'ASC')->paginate(5);
            }
            if($sortby == 'price_desc'){
                $advertisements = Advertisement::orderBy('price', 'DESC')->paginate(5);
            }
        }

        // display the trade page view with the advertisement data
        return view('trade.index')->with('advertisements', $advertisements);
    }

    /**
     * Display the details of the advertisement.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // check if the user is authenticated
        if(Auth::check()){
            // Show the status of favourite advertisement
            $user = auth()->user();
        
            $exists = Favourite::where("advertisement_id", $id)
                                ->where("user_id", "=", $user->id)->exists();
        } else{
            $exists = Favourite::where("advertisement_id", $id)->exists();
        }
        
        // Calculate the page view count for the unique users
        if(!Cookie::get('advertisement_viewed')){
            // Increase the view count of advertisement
            Advertisement::find($id)->increment('views');
            
            // Create a cookie before the response and set it for 1 day (1 day = 1440 minutes)
            Cookie::queue('advertisement_viewed', true, 1440);
        }

        // Get the advertisement data
        $advertisement = Advertisement::with('user')->where('id', $id)->first();

        // display the trade page detail view with the advertisement data
        return view('trade.details', compact('exists'))->with('advertisement', $advertisement);
    }

    /**
     * This method is used for rating the advertisement
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  Advertisement $advertisement
     * @return \Illuminate\Http\Response
     */
    public function advertisementRating(Request $request, Advertisement $advertisement){
        // data validation
        $request->validate(['rate' => 'required']);

        $user_id = auth()->user()->id;
        $advertisement_id = $request->input('advertisement_id');

        $exists = Rating::where('advertisement_id', $advertisement_id)
                        ->where('user_id', $user_id)->exists();

        // check if the advertisement is already rated
        if($exists) {
            $advertisement = Advertisement::find($request->advertisement_id);
            $rating = new \willvincent\Rateable\Rating;
            $rating->rating = $request->input('rate');
            $rating->user_id = auth()->user()->id;
            $rating->advertisement_id = $request->input('advertisement_id');

            // update the previous rating
            $advertisement->rateOnce($request->input('rate'));

            // display the success message for rating update
            return redirect()->back()->with('success', 'Advertisement rating updated successfully.');
        } else {
            // Rate the advertisement
            $advertisement = Advertisement::find($request->advertisement_id);
            $rating = new \willvincent\Rateable\Rating;
            $rating->rating = $request->input('rate');
            $rating->user_id = auth()->user()->id;
            $rating->advertisement_id = $request->input('advertisement_id');

            // save the advertisement rating
            $advertisement->ratings()->save($rating);
    
            // display the success message
            return redirect()->back()->with('success', 'Advertisement rated successfully.');
        }
    }
}