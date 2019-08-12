<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Advertise;
use App\Client;
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
        // $advertises = Advertise::all();
        // return view('advertisement.index', compact('advertises'));
        return redirect('admin/advertise/create');;
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
        $advertise = Advertise::with(['client','location'])->where('IsActive','1')->orderBy('created_at','DESC')->get();
       //dd($advertise);
        return view('advertisement.create',compact('clients','locations','advertise'));
    }

    public function deactiveAdvertise()
    {
        $clients = Client::all();
        $locations = Location::all();
        $advertise = Advertise::with(['client','location'])->where('IsActive','0')->orderBy('created_at','DESC')->get();
       //dd($advertise);
        return view('advertisement.deactive',compact('advertise'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd('manish');
        $this->validate($request, [
            'advertise_name'=>'required|max:100',
            'client_id'=>'required',
            'location_id'=>'required'
            ]);

        $data = $request->all();
        // echo json_encode($data['location_id']);die;
        foreach ($data['location_id'] as $key => $value) {
             $location =  Advertise::create([          
              'advertise_name'       => $data['advertise_name'],
              'location_id'          => $value,
              'client_id'            => $data['client_id'],
              'createdby'            =>Auth::user()->id,         
            ]);
        }
       
       // $location =  Advertise::create([          
       //        'advertise_name'       => $data['advertise_name'],
       //        'location_id'          => $data['location_id'],
       //        'client_id'            => $data['client_id'],
       //        'createdby'            =>Auth::user()->id,         
       //      ]);
       
       if(isset($location)) {
        return redirect()->route('advertise.index')
            ->with('message',
             'Advertise successfully added.');
        }else{
            return redirect()->route('advertise.index')
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
        $adve   = Advertise::findOrFail($id);
        $advertise = Advertise::with(['client','location'])->orderBy('created_at','DESC')->get();
        $clients = Client::all();
        $locations = Location::all();
        //$locationall = Location::all();
        return view('advertisement.create', compact('adve','advertise','clients','locations'));
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
            'advertise_name'=>'required|max:100',
        ]);

        $advertise = Advertise::findOrFail($id);
        $advertise->client_id = $request->input('client_id');
        $advertise->location_id = $request->input('location_id');
        $advertise->advertise_name = $request->input('advertise_name');
        $upate = $advertise->save();

        if(isset($upate)) {
        return redirect()->route('advertise.index')
            ->with('message',
             'Advertise successfully Updated.');
        }else{
            return redirect()->route('advertise.index')
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
    
    
    public function updateAdvertise($id, $keyword){
        
        if($keyword == 'deactive'){
           
            $location = Advertise::findOrFail($id); 
            $updatedata = $location->fill(array('IsActive'=>'0'))->save();
            return redirect('admin/advertise')->with('message',
             'Deactive Successfully.');
            
        }
        
        if($keyword == 'active'){
            $location = Advertise::findOrFail($id); 
            $updatedata = $location->fill(array('IsActive'=>'1'))->save();
            return redirect('admin/advertise')->with('message',
             'Active Successfully.');
            
        }
    }
}
