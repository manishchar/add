<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Websitesetting;
class WebsiteController extends Controller
{
    public function index()
    {  
        $webseting = Websitesetting::first();
        return view('admin/websitesetting',compact('webseting'));
    }


    public function websettingupd(Request $request)
    {   
        $this->validate($request, [
            'locktimeout'           => 'required',
        ]);


        $data  = $request->all();
        $websitesetting = Websitesetting::findOrFail($data['id']);
        $websitesetting->fill($data)->save();
        if($websitesetting){
            return \Redirect::back()->with('message','Update Successfully');
        }else{
            return \Redirect::back()->with('message','Action Failed...');
        }
       
    }


}
