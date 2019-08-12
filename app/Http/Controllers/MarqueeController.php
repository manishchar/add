<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Marquee;
use App\Location;
use App\Screen_type;
use App\Screen_size;
use Auth;

class MarqueeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function __construct()
    {
       // $this->middleware(['auth', 'clearance']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

public function location()
    {
        $locations = Location::all();
        return view('location.create1', compact('locations'));
    }

    public function index()
    {
        $marqueelist = Marquee::orderBy('id', 'desc')->get();
        //$locations = Location::with('city')->get();
        return view('location.marqueelist', compact('marqueelist'));
    }

    public function store(Request $request)
    {
        
        $this->validate($request, [
            'content'          =>'required',
           
            ]);

        $data = $request->all();
        $marquee =  Marquee::create([          
          'content'             => $data['content'],
                  
         ]);
       
       if(isset($marquee)) {
        return redirect('admin/marquee')
            ->with('message',
             'Marquee successfully added.');
        }else{
            return redirect('admin/marquee')
            ->with('message',
             'Action Failed Please try again.');
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
        $marquee = Marquee::findOrFail($id);
        $delete = $marquee->delete();

        if(isset($delete)) {
        return redirect('admin/marquee')
            ->with('message',
             'Marquee successfully Deleted.');
        }else{
            return redirect('admin/marquee')
            ->with('message',
             'Action Failed Please try again.');
        }
    }


}
