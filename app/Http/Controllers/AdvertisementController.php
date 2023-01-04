<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Favourite;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * This controller handles the user advertisement
 */
class AdvertisementController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // only the authenticated user can access the advertisement page and its function
        $this->middleware(['auth']);
    }

    /**
     * Display the advertisement page with the user advertisement data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the advertisement data created by the authenticated user
        $userid = auth()->user()->id;
        $advertisements = Advertisement::where('user_id', '=', $userid)
                                    ->orderBy('id', 'DESC')
                                    ->paginate(5);

        // Search 
        // check if the request has search keyword
        if($request->has('search')){
            // find the keyword in the advertisement data
            $advertisements = Advertisement::where('user_id', '=', $userid)
                                        ->join('users', 'advertisements.user_id', '=', 'users.id')
                                        ->select('users.*', 'advertisements.*')
                                        ->where(function($query){
                                            $query->where('users.name', 'iLIKE', '%' . request()->query('search') . '%');
                                            $query->orWhere('users.email', 'iLIKE', '%' . request()->query('search') . '%');
                                            $query->orWhere('advertisements.skilled_trades', 'iLIKE', '%' . request()->query('search') . '%');
                                            $query->orWhere('advertisements.title', 'iLIKE', '%' . request()->query('search') . '%');
                                            $query->orWhere('advertisements.description', 'iLIKE', '%' . request()->query('search') . '%');
                                            $query->orWhere('advertisements.address', 'iLIKE', '%' . request()->query('search') . '%');
                                            $query->orWhere('advertisements.price', 'iLIKE', '%' . request()->query('search') . '%');
                                            $query->orWhere('advertisements.image', 'iLIKE', '%' . request()->query('search') . '%');
                                            $query->orWhere('advertisements.phone', 'iLIKE', '%' . request()->query('search') . '%');
                                        })->orderBy('advertisements.id', 'DESC')->paginate(5);
        }

        // Sort Table
        $sort = $request->query('sort');
        $direction = $request->query('direction');

        // check if the request has sorting field value and direction value
        if($request->has('sort') and $request->has('direction')){
            // Sort the advertisement data 
            $advertisements = Advertisement::where('user_id', '=', $userid)
                                    ->orderBy($sort, $direction)
                                    ->paginate(5);
        }

        // Category Filter
        $category_filter = $request->query('category_filter');

        // check if the request has category filter value
        if($request->has('category_filter')){
            // Filter the advertisement data 
            $advertisements = Advertisement::where('user_id', '=', $userid)
                                        ->where('skilled_trades', $category_filter)
                                        ->orderBy('id', 'DESC')
                                        ->paginate(5);
        }

        // Sort Filter
        $sortby = $request->query('sortby');
        
        // check if the request has sort filter value
        if($request->has('sortby')){
            // Sort the advertisement data
            if($sortby == 'latest'){
                $advertisements = Advertisement::where('user_id', '=', $userid)
                                                ->orderBy('created_at', 'DESC')->paginate(5);
            }
            if($sortby == 'oldest'){
                $advertisements = Advertisement::where('user_id', '=', $userid)
                                                ->orderBy('created_at', 'ASC')->paginate(5);
            }
            if($sortby == 'title_asc'){
                $advertisements = Advertisement::where('user_id', '=', $userid)
                                                ->orderBy('title', 'ASC')->paginate(5);
            }
            if($sortby == 'title_desc'){
                $advertisements = Advertisement::where('user_id', '=', $userid)
                                                ->orderBy('title', 'DESC')->paginate(5);
            }
            if($sortby == 'price_asc'){
                $advertisements = Advertisement::where('user_id', '=', $userid)
                                                ->orderBy('price', 'ASC')->paginate(5);
            }
            if($sortby == 'price_desc'){
                $advertisements = Advertisement::where('user_id', '=', $userid)
                                                ->orderBy('price', 'DESC')->paginate(5);
            }
        }

        // display the advertisement page view with the advertisement data
        return view('advertisements.index')->with('advertisements', $advertisements);
    }

    /**
     * Show the form for creating a new advertisement.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // display the advertisement create page
        return view('advertisements.create');
    }

    /**
     * Store a newly created advertisement in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // data validation
        $request->validate([
            'skilled_trades' => 'required|nullable',
            'title' => 'required|min:8|max:100',
            'description' => 'required|min:8|max:400',
            'address' => 'required|min:8|max:50',
            'price' => 'required|numeric|min:0|max:1000',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3000',
            'phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15|nullable',
        ]);

        try {
            // Store the advertisement data in the database
            $advertisements = new Advertisement;

            $advertisements->user_id = auth()->user()->id;
            $advertisements->skilled_trades = $request->input('skilled_trades');
            $advertisements->title = $request->input('title');
            $advertisements->description = $request->input('description');
            $advertisements->address = $request->input('address');
            $advertisements->price = $request->input('price');

            // check if the request has image
            if($request->hasFile('image')){

                $file = $request->file('image');
                $name = $file->getClientOriginalName();
                $image = Image::make($file);
                $image->resize(350, 300);

                $filePath = 'images/' . $name;
                Storage::disk('s3')->put($filePath, $image->stream());
                $advertisements->image = $name;
            }
            $advertisements->phone = $request->input('phone');
           
            // save the advertisement
            $advertisements->save();

            // display the success message
            return redirect('/my-ads')->with('success', 'Advertisement created successfully.');
        } catch(\Exception $exception) {
            // display the error message if there is any exception
            return redirect('/my-ads')->with('error', 'Something went wrong. Please try again!')->withInput();
        }
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
        if(Auth::check()) {
            $user = auth()->user();

            // Show the status of favourite advertisement        
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

        // Get the authenticated user advertisement data
        $advertisement = Advertisement::with('user')->where('id', $id)->first();

        // display the advertisement detail view with the advertisement data
        return view('advertisements.details', compact('exists'))->with('advertisement', $advertisement);
    }

    /**
     * Show the form for editing the advertisement.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get the advertisement data with the help of id
        $advertisement = Advertisement::find($id);

        // display the advertisement form with the details for a specified advertisement
        return view('advertisements.edit')->with('advertisement', $advertisement);
    }

    /**
     * Update the specified advertisement in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // data validation
        $request->validate([
            'skilled_trades' => 'required|nullable',
            'title' => 'required|min:8|max:100',
            'description' => 'required|min:8|max:400',
            'address' => 'required|min:8|max:50',
            'price' => 'required|numeric|min:0|max:1000',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3000',
            'phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15|nullable',
        ]);

        try{
            // Update the advertisement data in the database
            $advertisement = Advertisement::find($id);

            $advertisement->user_id = auth()->user()->id;
            $advertisement->skilled_trades = $request->input('skilled_trades');
            $advertisement->title = $request->input('title');
            $advertisement->description = $request->input('description');
            $advertisement->address = $request->input('address');
            $advertisement->price = $request->input('price');

            // check if the request has image
            if($request->hasFile('image')){
                if(Storage::disk('s3')->exists('images/' . $advertisement->image)){
                    Storage::disk('s3')->delete('images/' . $advertisement->image);
                }

                $file = $request->file('image');
                $name = $file->getClientOriginalName();
                $image = Image::make($file);
                $image->resize(350, 300);

                $filePath = 'images/' . $name;
                Storage::disk('s3')->put($filePath, $image->stream());
                $advertisement->image = $name;
            }
            $advertisement->phone = $request->input('phone');
           
            // update the advertisement
            $advertisement->update();

            // display the success message
            return redirect('/my-ads')->with('success', 'Advertisement updated successfully.');
        } catch(\Exception $exception) {
            // display the error message if there is any exception
            return redirect('/my-ads')->with('error', 'Something went wrong. Please try again!')->withInput();
        }
    }

    /**
     * Remove the specified advertisement from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // find the advertisement with the help of id
            $advertisement = Advertisement::find($id);
            if(Storage::disk('s3')->exists('images/' . $advertisement->image)){
                Storage::disk('s3')->delete('images/' . $advertisement->image);
            }
            // delete the advertisement
            $advertisement->delete();
    
            // display the success message
            return redirect('/my-ads')->with('success', 'Advertisement deleted successfully.');
        } catch(\Exception $exception) {
            // display the error message if there is any exception
            return redirect('/my-ads')->with('error', 'Something went wrong. Please try again!');
        }
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
        if($exists){
            $advertisement = Advertisement::find($request->advertisement_id);
            $rating = new \willvincent\Rateable\Rating;
            $rating->rating = $request->input('rate');
            $rating->user_id = auth()->user()->id;
            $rating->advertisement_id = $request->input('advertisement_id');

            // update the previous rating
            $advertisement->rateOnce($request->input('rate'));

            // display the success message
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