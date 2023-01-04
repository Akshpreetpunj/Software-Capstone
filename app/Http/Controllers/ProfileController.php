<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

/**
 * This controller handles the profile page of the user. The user can change their profile 
 * details like name, email and password
 */
class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // only the authenticated user can access the profile page and its function
        $this->middleware(['auth']);
    }

    /**
     * This method is used for updating the user profile details
     */
    public function update(UpdateProfileRequest $request){
        try {  
            // Updating name and email
            auth()->user()->update($request->only('name', 'email'));

            // Updating password
            if($request->input('password')){
                auth()->user()->update([
                    'password' => bcrypt($request->input('password'))
                ]);
            }

            // display the success message
            return redirect()->route('profile')->with('success', 'Profile saved successfully.');
        }  catch (\Exception $exception) {
            // display the error message if there is any exception
            return redirect()->route('profile')->with('error', "Something went wrong. Please try again!")->withInput();
        }
    }
}