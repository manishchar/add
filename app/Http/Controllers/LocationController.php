<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Location;
use App\Screen_type;
use App\Screen_size;
use Auth;

class LocationController extends Controller
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
        $locations = Location::where('IsActive','1')->with(['city','screenSize','screenType'])->orderBy('id', 'desc')->get();
        //$locations = Location::with('city')->get();
        return view('location.index', compact('locations'));
    }
    public function deactiveLocation()
    {
        $locations = Location::where('IsActive','0')->with(['city','screenSize','screenType'])->orderBy('id', 'desc')->get();
        //$locations = Location::with('city')->get();
        return view('location.deactive', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    

    public function getCity()
    {
        $state_id = $_REQUEST['id'];
        //echo json_encode($state_id);die;
        //$states = DB::table('states')->get();
        $cities = DB::table('cities')->where('state_id',$state_id)->get();
        echo json_encode($cities);
        //dd($cities);
        //return view('location.create',compact('states','cities'));
    }

    public function create()
    {
        $states = DB::table('states')->get();
        $types = Screen_type::where('IsActive','1')->get();
        $sizes = Screen_size::where('IsActive','1')->get();
        $cities = array();
        //dd($screen_size);
        return view('location.create',compact('states','cities','sizes','types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'city_id'          =>'required',
            'state_id'      =>'required',
            'screen_name'      =>'required',
            'screen_id'          =>'required',
            'deviceId'          =>'required',
            'location'          =>'required|max:100',
            'screen_size'   =>'required|max:100',
            'screen_type'   =>'required|max:100',
            'latitude'      =>'required|max:100',
            'longitude'     =>'required|max:100'
            ]);

        $data = $request->all();
        $location =  Location::create([          
          'city_id'             => $data['city_id'],
          'state_id'            => $data['state_id'],
          'screen_name'         => $data['screen_name'],
          'screen_id'            => $data['screen_id'],
          'deviceId'            => $data['deviceId'],
          'location'            => $data['location'],
          'screen_size'         => $data['screen_size'],
          'screen_type'         => $data['screen_type'],
          'lat'                 => $data['latitude'],
          'lng'                 => $data['longitude'],
          'createdby'           =>Auth::user()->id,         
         ]);
       
       if(isset($location)) {
        return redirect()->route('location.index')
            ->with('message',
             'Location successfully added.');
        }else{
            return redirect()->route('location.index')
            ->with('message',
             'Action Failed Please try again.');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $location   = Location::findOrFail($id);
        $types = Screen_type::where('IsActive','1')->get();
        $sizes = Screen_size::where('IsActive','1')->get();
        $states = DB::table('states')->get();
        $cities = DB::table('cities')->where('state_id',$location->state_id)->get();
        $locationall = Location::all();
        //dd($location);
        return view('location.create', compact('types','sizes','states','cities','location','locationall'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'city_id'=>'required|max:100',
        ]);

        $location = Location::findOrFail($id);
        $location->city_id = $request->input('city_id');
        $location->state_id = $request->input('state_id');
        $location->screen_name = $request->input('screen_name');
        $location->screen_id = $request->input('screen_id');
        $location->deviceId = $request->input('deviceId');
        $location->location = $request->input('location');
        $location->screen_size = $request->input('screen_size');
        $location->screen_type = $request->input('screen_type');
        $location->lat = $request->input('latitude');
        $location->lng = $request->input('longitude');
        $upate = $location->save();

        if(isset($upate)) {
        return redirect()->route('location.index')
            ->with('message',
             'Location successfully Updated.');
        }else{
            return redirect()->route('client.index')
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
        $location = Location::findOrFail($id);
        $delete = $location->delete();

        if(isset($delete)) {
        return redirect()->route('location.index')
            ->with('message',
             'Location successfully Deleted.');
        }else{
            return redirect()->route('location.index')
            ->with('message',
             'Action Failed Please try again.');
        }
    }
    
    
    public function updateLocation($id, $keyword){
        
        if($keyword == 'deactive'){
           
            $location = Location::findOrFail($id); 
            $updatedata = $location->fill(array('IsActive'=>'0'))->save();
            return redirect('admin/location')->with('message',
             'Deactive Successfully.');
            
        }
        
        if($keyword == 'active'){
            $location = Location::findOrFail($id); 
            $updatedata = $location->fill(array('IsActive'=>'1'))->save();
            return redirect('admin/location')->with('message',
             'Active Successfully.');
            
        }
    }
}
