<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

/**
 * This controller handles all the users in the application
 */
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // only the admin can access the users page and their details
        $this->middleware(['is_admin']);
    }

    /**
     * Display users page with the users data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the users data 
        $users = User::orderBy('id', 'ASC')->paginate(5);

        // Search
        $search = $request->query('search');

        // check if the request has search keyword
        if($request->has('search')){
            // find the keyword in the users data
            $users = User::where('id', 'iLIKE', '%' . $search . '%')
                        ->orWhere('name', 'iLIKE', '%' . $search . '%')
                        ->orWhere('email', 'iLIKE', '%' . $search . '%')
                        ->orWhere('created_at', 'iLIKE', '%' . $search . '%')
                        ->orderBy('id', 'ASC')
                        ->paginate(5);
        }

        // Sort Table
        $sort = $request->query('sort');
        $direction = $request->query('direction');

        // check if the request has sorting field value and direction value
        if($request->has('sort') and $request->has('direction')){
            // Sort the users data 
            $users = User::orderBy($sort, $direction)->paginate(5);
        }

        // display the users page view with the users data
        return view('users.index')->with('users', $users);
    }

    /**
     * Remove the user from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // find the user with the help of id
            $user = User::where('id', $id);
            // delete the user
            $user->delete();
            
            // display the success message
            return redirect()->back()->with('success', 'Account deleted successfully.');
        } catch(\Exception $exception) {
            // display the error message if there is any exception
            return redirect()->back()->with('error', 'Something went wrong. Please try again!');
        }
    }
}