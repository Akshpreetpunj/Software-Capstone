<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * This controller handles the about page
 */
class AboutController extends Controller
{
    /**
     * Show the about page
     */
    public function index(){
        $title = "About Us";
        // display the about page view with the title
        return view('about')->with('title', $title);
    }
}