<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Advertisement;
use App\client;
use App\Location;
use Auth;

class AdvertiseController extends Controller
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
    

    public function index()
    {
        $locations = Advertisement::all();
        return view('advertisement.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        $locations = Location::all();
       
        return view('advertisement.create',compact('clients','locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd('manish');
        $this->validate($request, [
            'advertisement'=>'required|max:100',
            'client_id'=>'required',
            'location_id'=>'required'
            ]);

        $data = $request->all();
        echo json_encode($data);die;
        $location =  Location::create([          
          'name'                 => $data['name'],
          'createdby'            =>Auth::user()->id,         
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
        $locationall = Location::all();
        return view('client.create', compact('location','locationall'));
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
            'name'=>'required|max:100',
        ]);

        $location = Location::findOrFail($id);
        $location->name = $request->input('name');
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
