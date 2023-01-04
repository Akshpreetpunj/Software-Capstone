<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Only guest user can access the login page
        $this->middleware('guest')->except('logout');
    }

    /**
     * This method is used for checking the login details input by the user. If the login details are correct
     * user will be successfully logged in to the web application.
     */
    public function login(Request $request){
        // data validation
        $input = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
        ]);
        
        try {  
            // Check the login details  
            if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password']))){
                // Admin login
                if(auth()->user()->is_admin == 1) {
                    if($request->has('remember')){
                        // Create a cookie for the admin email and password and set it for 1 day (1 day = 1440 minutes)
                        Cookie::queue('email', $request->email, 1440);
                        Cookie::queue('password', $request->password, 1440);
                    }
                    return redirect()->route('admin.home');
                } else {
                    // User login
                    if($request->has('remember')){
                        // Create a cookie for the user email and password and set it for 1 day (1 day = 1440 minutes)
                        Cookie::queue('email', $request->email, 1440);
                        Cookie::queue('password', $request->password, 1440);
                    }
                    return redirect()->route('home');
                }
            } else {
                // display error message on wrong inputs
                return redirect()->route('login')->with('error', 'These credentials do not match our records. Please try again!')
                                                 ->withInput();
            }
        } catch (\Exception $exception) {
            // display error message if there is any exception
            return redirect()->route('login')->with('error', "Something went wrong. Please try again!")->withInput();
        }
    }
}