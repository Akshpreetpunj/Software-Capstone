<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * This controller handles the web application settings like banner image and announcement
 */
class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // only admin can access the settings page and its function
        $this->middleware(['is_admin']);
    }
    
    /**
     * Display the settings page with the settings data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the settings data
        $settings = Setting::orderBy('id', 'DESC')->paginate(5);

        // Search 
        $search = $request->query('search');

        // check if the request has search keyword
        if($request->has('search')){
            // find the keyword in the settings data
            $settings = Setting::where('announcement', 'iLIKE', '%' . $search . '%')
                        ->orWhere('publish_status', 'iLIKE', '%' . $search . '%')
                        ->orWhere('banner_image', 'iLIKE', '%' . $search . '%')
                        ->orderBy('id', 'DESC')
                        ->paginate(5);
        }

        // Sort Table
        $sort = $request->query('sort');
        $direction = $request->query('direction');

        // check if the request has sorting field value and direction value
        if($request->has('sort') and $request->has('direction')){
            // Sort the settings data 
            $settings = Setting::orderBy($sort, $direction)->paginate(5);
        }

        // display the settings page view with the settings data
        return view('settings.index')->with('settings', $settings);
    }

    /**
     * Show the form for creating a setting (Banner Image & Announcement).
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // display the settings create page
        return view('settings.create');
    }

    /**
     * Store a newly created setting (Banner Image & Announcement).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // data validation
        $request->validate([
            'banner_image' => 'image|required|mimes:jpeg,png,jpg,gif,svg|max:3000',
            'announcement' => 'nullable|max:100',
        ]);

        try {
            // Store the setting data in the database
            $settings = new Setting;
            
            // check if the request has banner image
            if($request->hasFile('banner_image')){

                $file = $request->file('banner_image');
                $name = $file->getClientOriginalName();
                $banner_image = Image::make($file);
                $banner_image->resize(960, 240);

                $filePath = 'banner_images/' . $name;
                Storage::disk('s3')->put($filePath, $banner_image->stream());
                $settings->banner_image = $name;
            }
            $settings->announcement = $request->input('announcement');
            $settings->publish_status = $request->input('publish_status');
           
            // save the settings
            $settings->save();

            // display the success message
            return redirect('/settings')->with('success', 'Banner image & announcement added successfully.');
        } catch(\Exception $exception) {
            // display the error message if there is any exception
            return redirect('/settings')->with('error', 'Something went wrong. Please try again!')->withInput();
        }
    }

    /**
     * Show the form for editing the settings (banner Image & Announcement).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get the settings data with the help of id
        $setting = Setting::find($id);
        // display the settings form with the details for a specified setting
        return view('settings.edit')->with('setting', $setting);
    }

    /**
     * Update the specified settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // data validation
        $request->validate([
            'banner_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3000',
            'announcement' => 'nullable|max:100',
        ]);

        try{
            // Store the updated settings data in the database
            $setting = Setting::find($id);
            
            // check if the request has banner image
            if($request->hasFile('banner_image')){
                if(Storage::disk('s3')->exists('banner_images/' . $setting->banner_image)){
                    Storage::disk('s3')->delete('banner_images/' . $setting->banner_image);
                }

                $file = $request->file('banner_image');
                $name = $file->getClientOriginalName();
                $banner_image = Image::make($file);
                $banner_image->resize(960, 240);

                $filePath = 'banner_images/' . $name;
                Storage::disk('s3')->put($filePath, $banner_image->stream());
                $setting->banner_image = $name;
            }
            $setting->announcement = $request->input('announcement');
            $setting->publish_status = $request->input('publish_status');
           
            // update the settings
            $setting->update();

            // display the success message
            return redirect('/settings')->with('success', 'Banner image & announcement updated successfully.');
        } catch(\Exception $exception){
            // display the error message if there is any exception
            return redirect('/settings')->with('error', 'Something went wrong. Please try again!')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // find the settings with the help of id
            $setting = Setting::find($id);
            if(Storage::disk('s3')->exists('banner_images/' . $setting->banner_image)){
                Storage::disk('s3')->delete('banner_images/' . $setting->banner_image);
            }
            // delete the settings
            $setting->delete();
    
            // display the success message
            return redirect('/settings')->with('success', 'Banner image & announcement deleted successfully.');
        } catch(\Exception $exception){
            // display the error message if there is any exception
            return redirect('/settings')->with('error', 'Something went wrong. Please try again!');
        }
    }
}