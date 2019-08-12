<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Advertise;
use App\client;
use App\Location;
use App\Schedule;
use Auth;
use Pawlox\VideoThumbnail\Facade\VideoThumbnail;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function __construct()
    {
       // $this->middleware(['auth', 'clearance']);

        //$role_id = Auth::user()->user_type;
        //$modulePermission = Illuminate\Support\Facades\DB::table('role_has_permissions')->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')->where('permissions.parent_id','0')->where('role_id',$role_id)->select('permissions.id')->get()->pluck('id')->toArray();
        //dd('manish');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


public function demo(){
//$schedules = Schedule::get();
$search = 'd';
$schedules = Schedule::with(['advertise','client',"advertise.location"])-> whereHas('client', function($query) use ($search){
        $query->where('fname','LIKE',$search.'%');
    })->orWhereHas('advertise', function($query) use ($search){
        $query->where('advertise_name','LIKE',$search.'%');
    })->orWhereHas('advertise.location', function($query) use ($search){
        $query->where('screen_name','LIKE',$search.'%');
    })->orderBy('created_at', 'DESC')->get();

     return view('schedule.list', compact('schedules'));
}
public function getRemainingTime(Request $request){

    $toDate = date('Y-m-d',strtotime($request->to));
    $fromDate = date('Y-m-d',strtotime($request->from));
    $videoLength=Advertise::videoLenght($request->id,$fromDate,$toDate);
    $response = array('status'=>'success','videoLenght'=>$videoLength);
    echo json_encode($response);die;
}
public function getSchedule(Request $request)
    {
        
        $schedule = Schedule::with(['advertise','client',"advertise.location"])->where('id',$request->id)->first();

        $videoLength=Advertise::videoLenghtForAdd($schedule->location_id,$schedule->fromDate,$schedule->toDate);
        $data['advertises']=$schedule;
        $data['videoLength']=$videoLength;
        echo json_encode($data);die;
        $id = $_POST['id'];
        $advertises = Advertise::with(['client','location'])->where('client_id',$client_id)->orderBy('created_at', 'DESC')->get();
        foreach ($advertises as $key => $value) {
            $videoLength[]=Advertise::videoLenght($value->id,$from,$to);
        }
        
        echo json_encode($data);
    }
    public function getAdds()
    {
        $client_id = $_POST['client_id'];
        $from = date('Y-m-d',strtotime($_POST['from']));
        $to = date('Y-m-d',strtotime($_POST['to']));
        $advertises = Advertise::with(['client','location'])->where('client_id',$client_id)->where('IsActive','1')->orderBy('created_at', 'DESC')->get();
        foreach ($advertises as $key => $value) {
            $videoLength[]=Advertise::videoLenghtForAdd($value->location_id,$from,$to);
        }
        $data['advertises']=$advertises;
        $data['videoLength']=$videoLength;
        echo json_encode($data);
    }
    public function upload(Request $request){
        

           $image_file         = $request->file('video');
           $destinationPath    = 'public/tmp/';
           $image_name         = $image_file->getClientOriginalName();
           $extention          = $image_file->getClientOriginalExtension();

          $image = value(function() use ($image_file)
              {
                $filename = time().'.'. $image_file->getClientOriginalExtension();
                  return strtolower($filename);
              });
            echo $request->file('video')->move($destinationPath, $image);
           die;
        // if ($_POST) { 
        // $folder = "./public/tmp/";  
        // echo move_uploaded_file($_FILES["file"]["tmp_name"], "$folder" . $_FILES["file"]["name"]); 
        // }
            echo "string";
    }


     public function fileUploadPost(Request $request)
    {
        $path = 'public/tmp/';
        if(isset($_POST['formType'])){
            // edit
            $image_file         = $request->file('file');
            $oldVideoUrl=$_POST['oldVideoUrl'];
            $schedule = Schedule::findOrFail($request->schedule_id);
             //$schedule = new Schedule;
            if($image_file){
                // file selected
                if($oldVideoUrl !=''){
                    if(file_exists($path.$oldVideoUrl)){
                       if(unlink($path.$oldVideoUrl)){
                        //echo "success delete";
                       }
                    }
                }
                $destinationPath    = $path;
                $image_name         = $image_file->getClientOriginalName();
                $extention          = $image_file->getClientOriginalExtension();
                $image = value(function() use ($image_file){
                $filename = time().'.'. $image_file->getClientOriginalExtension();
                return strtolower($filename);
                });
                $request->file('file')->move($destinationPath, $image);
                $schedule->videoUrl = $image;
            }
            
            $schedule->iteration = $request->iteration;
            $schedule->at_end = $request->at_end;
            $schedule->fromDate = date('Y-m-d',strtotime($request->startDate));
            $schedule->toDate = date('Y-m-d',strtotime($request->endDate));
           
            $schedule->videoLength = $request->duration;
            $schedule->videoTotalLength = ($request->iteration*$request->duration);
            $schedule->updatedBy = Auth::user()->id;
            $flag= $schedule->save();

        if($flag){
            $response = array('status'=>'success','message'=>'You have successfully Update data.');
        }else{
            $response = array('status'=>'failed','message'=>'You have Failed Update data.');
        }
        echo json_encode($response);
        die;
        }   
        
           $image_file         = $request->file('file');
           $destinationPath    = 'public/tmp/';
           $destinationNew    = '/tmp/';
           $image_name         = $image_file->getClientOriginalName();
           $extention          = $image_file->getClientOriginalExtension();
           $image = value(function() use ($image_file){
               $filename = time().'.'. $image_file->getClientOriginalExtension();
               return strtolower($filename);
              });
        $request->file('file')->move($destinationPath, $image);
        
        //VideoThumbnail::createThumbnail($videoUrl, $storageUrl, $fileName, $second, $width = 640, $height = 480);
//         echo $destinationNew.$image;
//         $thumb = VideoThumbnail::createThumbnail(public_path('tmp/1542870192.mp4'),public_path("tmp/img"), 'thumb.jpg', 3, 600, 600);
// dd($thumb);
$adve   = Advertise::findOrFail($request->advertise_id);
        $schedule = new Schedule;
        $schedule->iteration = $request->iteration;
        $schedule->at_end = $request->at_end;
        $schedule->client_id = $request->client_id;
        $schedule->advertise_id = $request->advertise_id;
        $schedule->location_id = $adve->location_id;
        $schedule->fromDate = date('Y-m-d',strtotime($request->from));
        $schedule->toDate = date('Y-m-d',strtotime($request->to));
        $schedule->videoUrl = $image;
        $schedule->videoLength = $request->duration;
        $schedule->videoTotalLength = ($request->iteration*$request->duration);
        $schedule->createdby = '1';
        $schedule->save();
// $schedules = array(
//     'client_id'=>$_POST['client_id'],
//     'advertise_id'=>$_POST['advertise_id'],
//     'fromDate'=>$_POST['from'],
//     'toDate'=>$_POST['to'],
//     'videoUrl'=>$image,
//     'videoLength'=>$_POST['videoLength'],
// );

        $response = array('status'=>'success','message'=>'You have successfully upload file.','data'=>$schedule);
        echo json_encode($response);
    }


    public function index()
    {
        $advertises = Advertise::with(['client','location'])->get();
        $clients = Client::all();
        $locations = Location::all();
        return view('schedule.index', compact('advertises','clients','locations'));
        //return redirect('admin/advertise/create');;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

public function deleteVideo(Request $request){
    //print_r($_POST);
    $schedule = Schedule::find($request->id);
    $schedule->isActive = '0';
    $flag= $schedule->save();
    if($flag){
        $response = array('status'=>'success','code'=>'200','message'=>'Update Success');    
    }else{
        $response = array('status'=>'failed','code'=>'500','message'=>'Update Failed');
    }
    echo json_encode($response);
}
   
public function changeStatus(Request $request){
    //print_r($_POST);


    $schedules = Schedule::with(['advertise','client',"advertise.location"])->where('id','=',$request->id)->get();


    $schedule = Schedule::find($request->id);
    $schedule->status = '3';
    //$schedule->status = $request->status;
    $flag= $schedule->save();
    //$schedule = Schedule::find($request->id);
    if($schedules){
        $response = array('status'=>'success','code'=>'200','message'=>'Update Success','data'=>$schedules);    
    }else{
        $response = array('status'=>'failed','code'=>'500','message'=>'Update Failed');
    }
    echo json_encode($response);
}

public function repushStatus(Request $request){
    //print_r($_POST);


    $schedules = Schedule::with(['advertise','client',"advertise.location"])->where('id','=',$request->id)->get();


    $schedule = Schedule::find($request->id);
    $schedule->status = '0';
    //$schedule->status = $request->status;
    $flag= $schedule->save();
    //$schedule = Schedule::find($request->id);
    if($schedules){
        $response = array('status'=>'success','code'=>'200','message'=>'Update Success','data'=>$schedules);    
    }else{
        $response = array('status'=>'failed','code'=>'500','message'=>'Update Failed');
    }
    echo json_encode($response);
}
    

public function getScheduleRecord(){
    
        $draw = $_REQUEST['draw'];
        $start = $_REQUEST['start'];
        $length = $_REQUEST['length'];
        $searchArray = $_REQUEST['search'];
        $search = $searchArray['value'];
                $condition['IsActive'] = "1";

        if(isset($_REQUEST['location'])){
        	if($_REQUEST['location'] !='0'){
            $condition['location_id'] = $_REQUEST['location'];
        	}
        }
if($search!=''){
	$totalCount = Schedule::with(['advertise','client',"advertise.location"])->whereHas('client', function($query) use ($search){
	$query->where('fname','LIKE','%'.$search.'%');
	})->orWhereHas('advertise', function($query) use ($search){
	$query->where('advertise_name','LIKE','%'.$search.'%');
	})->orWhereHas('advertise.location', function($query) use ($search){
	$query->where('screen_name','LIKE','%'.$search.'%');
	$query->orWhere('location','LIKE','%'.$search.'%');
	})->where($condition)->get()->count();
}else{
	$totalCount = Schedule::with(['advertise','client',"advertise.location"])->where($condition)->get()->count();
}

   if($search!=''){
     $schedules = Schedule::with(['advertise','client',"advertise.location"])->whereHas('client', function($query) use ($search){
        $query->where('fname','LIKE','%'.$search.'%');
    })->orWhereHas('advertise', function($query) use ($search){
        $query->where('advertise_name','LIKE','%'.$search.'%');
    })->orWhereHas('advertise.location', function($query) use ($search){
        $query->where('screen_name','LIKE','%'.$search.'%');
        $query->orWhere('location','LIKE','%'.$search.'%');
    })->where($condition)->offset($start)->limit($length)->orderBy('created_at', 'DESC')->get();
}else{
     $schedules = Schedule::with(['advertise','client',"advertise.location"])->where($condition)->offset($start)->limit($length)->orderBy('created_at', 'DESC')->get();
}

//$totalCount = count($schedules);
if(count($schedules) >0){
            foreach ($schedules as $key => $schedule) {
              $status =$action = "";
                if($schedule->IsActive == '1'){
                    if($schedule->status == '0'){
                            $status = "<span class='badge badge-warning'>New</span>";
                            $action = "<button onclick='statusChange(1,".$schedule->id.")'>Push</button>";
                    }else if($schedule->status == '1'){
                            $status = "<span class='badge badge-info'>Done</span>";
                            $action = "<button onclick='deleteVideo(".$schedule->id.")'>Delete</button>";
                    }else if($schedule->status == '3'){
                            $status = "<span class='badge badge-info'>Panding</span>";
                            //$action = "<button onclick='deleteVideo(".$schedule->id.")'>Delete</button>";
                    }else if($schedule->status == '4'){
                            $status = "<span class='badge badge-primary'>Online</span>";
                            //$action = "<button onclick='deleteVideo(".$schedule->id.")'>Delete</button>";
                    }
$action .='<button type="button" class="btn" data-toggle="modal" onclick="edit('.$schedule->id.')">Edit</button>';
$action .='&nbsp;|&nbsp;<button type="button" class="btn" data-toggle="modal" onclick="view('.$schedule->id.')">View</button>';
$action .= "&nbsp;|&nbsp;<button class='btn' onclick='rePush(0,".$schedule->id.")'>Repush</button>";
$action .= "&nbsp;|&nbsp;<button class='btn btn-danger' onclick='deleteVideo(".$schedule->id.")'>Delete</button>";

                }else{
                    $status = "<span class='badge badge-danger'>Delete</span>";
                    $action = "<button class='btn btn-warning'>Resume</button>";
                }
                
                
               $result=array(
                    'sno'   =>$key+1,
                    'location'   =>$schedule->advertise->location->location,
                    'screen'   =>$schedule->advertise->location->screen_name,
                    'client_name'   =>$schedule->client->fname.' '.$schedule->client->lname,
                    'advertise'     =>$schedule->advertise->location->id.'|'.$schedule->advertise->location->deviceId,
                    'fromDate'      =>date('d-M-Y',strtotime($schedule->fromDate)),
                    'toDate'        =>date('d-M-Y',strtotime($schedule->toDate)),
                    'videoLength'   =>$schedule->videoLength,
                    'IsActive'      =>$status,
                    'action'      =>$action
               );
                $scheduleData[] =$result;
            }
}else{
    $scheduleData =array();
    //$totalCount = 0;
}
//dd($totalCount);
        $data=array(
              "draw"=> $draw,
              "recordsTotal"=> $totalCount,
              "recordsFiltered"=> $totalCount,
              "data"=>$scheduleData
        );
        echo json_encode($data);
    }
    public function create()
    {
        $clients = Client::all();
        $locations = Location::all();
        $advertise = Advertise::with(['client','location'])->get();
       //dd($advertise);
        return view('advertisement.create',compact('clients','locations','advertise'));
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
        // foreach ($data['location_id'] as $key => $value) {
        //      $location =  Advertise::create([          
        //       'advertise_name'       => $data['advertise_name'],
        //       'location_id'          => $value,
        //       'client_id'            => $data['client_id'],
        //       'createdby'            =>Auth::user()->id,         
        //     ]);
        // }
       
       $location =  Advertise::create([          
              'advertise_name'       => $data['advertise_name'],
              'location_id'          => $data['location_id'],
              'client_id'            => $data['client_id'],
              'createdby'            =>Auth::user()->id,         
            ]);
       
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
        $advertise = Advertise::with(['client','location'])->get();
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
