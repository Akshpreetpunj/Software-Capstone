<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

/**
 * This controller handles the home page
 */
class HomeController extends Controller
{
    /**
     * Show the application home page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get the setting data (Banner Image & Announcement)
        $settings = Setting::where('publish_status', 'on')->orderBy('id', 'ASC')->get();
        // display the home page view with the setting data
        return view('home')->with('settings', $settings);
    }

    /**
     * Show the admin home page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome(){
        // display the home page view
        return view('home');
    }
}