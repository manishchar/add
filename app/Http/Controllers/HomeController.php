<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Session;
use App\Jobmaster;
use App\Candidatemaster;
use App\InterviewSchedule;
use App\Organizationmaster;
use App\Client;
use App\Schedule;
use App\Location;
use Illuminate\Support\Facades\DB;
use Mail;
use App\Mail\ContactMail;
use App\Mail\Forgot;
use App\Websitesetting;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware(['auth', 'isAdmin','clearance']);
        //$this->middleware(['auth','clearance']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function my_profile(){
        // echo "string";
        // die;
        if(session()->has('front-login')){
            $session = session()->get('front-login')[0];
            //echo $session['id'];
            $client = Client::where('id',$session['id'])->first();
           // die;
            return view('profile',compact('client'));
        }else{
              return redirect('/');
        }
        
       
        
    }

    public function forgot(Request $request){
        return view('forgot');
    }

    public function forgot_submit(Request $request){
        //print_r($request);
        $data = $request->all();
        $client = Client::where('email',$data['email'])->first();
        if (empty($client)) {
            return back()->with('error','Invalid Email Try Again!');
        }


        $getWebInfo = Websitesetting::select('website_name', 'website_logo', 'email', 'address', 'mobile')->first();
         $content = [
             'title'         => 'Forgot Password with FirstAd', 
             'address'       => $getWebInfo->address,
             'mobile'        => $getWebInfo->mobile,
             'website_name'  => $getWebInfo->website_name,
             'website_logo'  => $getWebInfo->website_logo,
             'email'         => $getWebInfo->email,
             'client'       => $client,
             'name'          => $client->fname.' '.$client->lname,
             ];

        $receiverAddress = array($data['email']);
        $mail = Mail::to($receiverAddress)->bcc('manish09.chakravarti@gmail.com')->send(new Forgot($content) );
        if (count(Mail::failures()) > 0) {
            return back()->with('error','Please Try Again!');
        }else {
            //echo "No errors, all sent successfully!";
            return back()->with('message','Reset password send in your Email');
        }

        
    }

    public function forgot_password($id){
        //dd(decrypt($id));
        return view('forgot_password',compact('id'));
    }
    
    public function forgot_password_submit(Request $request){
        $this->validate($request, [
            'user_id'              => 'required',
            'password'              => 'required|min:4',
            'password_confirmation' => 'required|same:password'
        ]);
        $data = $request->all();
        $id = decrypt($data['user_id']);
        $client = Client::find($id);
        $new_password = sha1($data['password']);
        $client->password = $new_password;
        if($client->save()){
            return back()->with('message','Password change Successfully');
        }else{
            return back()->with('error','Please Try Again!');
        }
    }

    public function change_password_submit(Request $request){
        $this->validate($request, [
        'old_password'          => 'required',
        'password'              => 'required|min:4',
        'password_confirmation' => 'required|same:password'
        ]);
        $data = $request->all();
        $session = session()->get('front-login')[0];
        $client = Client::find($session['id']);
        $old_password = sha1($data['old_password']);
        $new_password = sha1($data['password']);
        if($old_password == $client->password){
            // update password
            //$flight = App\Flight::find(1);

            $client->password = $new_password;
            if($client->save()){
                return back()->with('message','Password change Successfully');
            }else{
                return back()->with('error','Please Try Again!');
            }
            
        }else{
            //echo "error worng";
              return back()->with('error','The specified password does not match the database password');

        }
    }
    public function change_password(){
        if(session()->has('front-login')){
            $session = session()->get('front-login')[0];
            //echo $session['id'];
            $client = Client::where('id',$session['id'])->first();
           // die;
            return view('change_password',compact('client'));
        }else{
              return redirect('/');
        }
        
    }
    public function send(Request $request){
        $data=$request->all();

        $user=array(
            'name'=>$data['name'],
            'email'=>$data['email'],
            'number'=>$data['number'],
            'message'=>$data['message']
        );
         $content = [
             'title'      => 'Enquiry with FirstAd', 
             'name'       => $data['name'],
             'email'       => $data['email'],
             'number'       => $data['number'],
             'message'       => $data['message']
             ];

        $receiverAddress = array('info@firstaddigital.com');
        $mail = Mail::to($receiverAddress)->bcc('manish09.chakravarti@gmail.com')->send(new ContactMail($content) );
        if (count(Mail::failures()) > 0) {
            $response = array('status'=>'failed');
        }else {
         $response = array('status'=>'success','message'=>'Message Send Success');
        }
        echo json_encode($response);
    }
    public function list(){
        //dd('test');
        if(!session()->has('front-login')){
            return redirect('/');
        }
        $id = session()->get('front-login')[0]['id'];

        $client_id =$id;
        $condition['client_id'] =$id;
        $dateFlag = false;
        if(isset($_REQUEST['from']) && isset($_REQUEST['to'])) {
            $from = date('Y-m-d',strtotime($_REQUEST['from']));
            $to = date('Y-m-d',strtotime($_REQUEST['to']));
            $dateFlag = true;
        }else if(isset($_REQUEST['from'])){
            $to = $from = date('Y-m-d',strtotime($_REQUEST['from']));
            $dateFlag = true;
        }else if(isset($_REQUEST['to'])){
           $from = $to = date('Y-m-d',strtotime($_REQUEST['to']));
           $dateFlag = true;
        }

        if($dateFlag){
           
            //$matchThese = ['fromDate' => $from, 'toDate' => 'another_value', ...];
           //  // if you need another group of wheres as an alternative:
             //$orThose = ['yet_another_field' => 'yet_another_value', ...];
//dd($condition);
           //$schedules = Schedule::with('advertise')->where('fromDate', '>=', $from)->where('toDate', '<=', $to)->where($condition)->get();

           //$schedules = Schedule::with('advertise')->where('fromDate', '>=', $from)->orWhere('toDate', '<=', $from)->where('fromDate', '>=', $to)->orWhere('toDate', '<=', $to)->where($condition)->get();

           //$schedules = Schedule::with('advertise')->where($condition)->where('fromDate', '>=', $from)->where('toDate', '<=', $to)->get();

           $schedules = Schedule::with('advertise','advertise.location')->where($condition)->whereBetween('fromDate', [$from, $to])->orWhere('toDate','>=', $to)->where("IsActive","1")->where('status','!=','0')->orderBy('location_id', 'desc')->get(); 
        }else{
            $schedules = Schedule::with('advertise','advertise.location')->where($condition)->where("IsActive",'=',"1")->where('status','!=','0')->orderBy('location_id', 'desc')->get();
        }
        $clients = Client::where('IsActive','1')->where('id',$client_id)->get();
        $total=Schedule::where($condition)->where("IsActive",'=',"1")->where('status','!=','0')->orderBy('location_id', 'desc')->get();
        $wordCount = $total->count();
        //dd(compact('schedules','clients'));
        return view('list',compact('schedules','clients','client_id','wordCount'));
    }
    
     public function list1(){
        //dd('test');
        if(!session()->has('front-login')){
            return redirect('/');
        }
        $id = session()->get('front-login')[0]['id'];

        $client_id =31;//$id;
        $condition['client_id'] =31;//$id;
        $dateFlag = false;
        if(isset($_REQUEST['from']) && isset($_REQUEST['to'])) {
            $from = date('Y-m-d',strtotime($_REQUEST['from']));
            $to = date('Y-m-d',strtotime($_REQUEST['to']));
            $dateFlag = true;
        }else if(isset($_REQUEST['from'])){
            $to = $from = date('Y-m-d',strtotime($_REQUEST['from']));
            $dateFlag = true;
        }else if(isset($_REQUEST['to'])){
           $from = $to = date('Y-m-d',strtotime($_REQUEST['to']));
           $dateFlag = true;
        }

        if($dateFlag){
           
            //$matchThese = ['fromDate' => $from, 'toDate' => 'another_value', ...];
           //  // if you need another group of wheres as an alternative:
             //$orThose = ['yet_another_field' => 'yet_another_value', ...];
//dd($condition);
           //$schedules = Schedule::with('advertise')->where('fromDate', '>=', $from)->where('toDate', '<=', $to)->where($condition)->get();

           //$schedules = Schedule::with('advertise')->where('fromDate', '>=', $from)->orWhere('toDate', '<=', $from)->where('fromDate', '>=', $to)->orWhere('toDate', '<=', $to)->where($condition)->get();

           //$schedules = Schedule::with('advertise')->where($condition)->where('fromDate', '>=', $from)->where('toDate', '<=', $to)->get();

           $schedules = Schedule::with('advertise','advertise.location')->where($condition)->whereBetween('fromDate', [$from, $to])->orWhere('toDate','>=', $to)->where("IsActive","1")->where('status','!=','0')->orderBy('location_id', 'desc')->get(); 
        }else{
            $schedules = Schedule::with('advertise','advertise.location')->where($condition)->where("IsActive",'=',"1")->where('status','!=','0')->orderBy('location_id', 'desc')->get();
        }
        $clients = Client::where('IsActive','1')->where('id',$client_id)->get();
        $total=Schedule::where($condition)->where("IsActive",'=',"1")->where('status','!=','0')->orderBy('location_id', 'desc')->get();
        $wordCount = $total->count();
        //dd(compact('schedules','clients'));
        return view('list',compact('schedules','clients','client_id','wordCount'));
    }
    
    public function user_logout(){
        session()->forget('front-login');
        return redirect('/');
    }
    public function user_login(Request $request){
        $email = $request->email;
        $password = sha1($request->password);
        $client = client::where('email',$email)->where('password',$password)->where('IsActive','1')->first();

        if(!empty($client)){
$request->session()->push('front-login', ['id'=>$client->id,'email'=>$client->email,'fname'=>$client->fname]);
 $response = array('status'=>'success','data'=>$client);  
        }else{
           $response = array('status'=>'failed','message'=>'Invalid Email or Password'); 
        }
        
//     //    $request->session()->forget('front-login');
//         if ($request->session()->has('front-login')) {
//     print_r($request->session()->get('front-login'));
// }else{
//     $request->session()->push('front-login', ['id'=>$client->id,'email'=>$client->email]);
// }
     
      echo json_encode($response);
    }
    public function index($key=null)
    {
        // echo $key;
        // die;
  //      $schedules = Schedule::with('advertise')->get();
       // $locations = Location::get();

$locations = Location::with(['allAdvertise'])->get();

        $schedules =DB::table('locations')
        ->Join('advertises', 'locations.id', '=', 'advertises.location_id')
        ->join('schedules', 'advertises.id', '=', 'schedules.advertise_id')
        ->select('locations.id','locations.location','locations.screen_name', 'advertises.advertise_name', 'schedules.videoUrl')->limit(6)->get();
            $slot =$availbleSlot= 0;
        $totalScreen = Location::where('IsActive','1')->count();    

        $videoTotalLength = (Schedule::where('IsActive','1')->sum('videoTotalLength'));         
      //dd(config('constants.TIME'));    
        $totalSecond = config('constants.TIME')*60*60;
        $availbleSecond = ($totalSecond - $videoTotalLength);
        $slot = ((($totalSecond*$totalScreen)/30)/60)/$totalScreen;
        $availbleSlot = ((($availbleSecond*$totalScreen)/30)/60)/$totalScreen;
//dd((int)floor($slot));
        $clients = Client::where('IsActiveFront','1')->where('IsActive','1')->get();
        return view('home',compact("locations",'schedules','clients','slot','availbleSlot','totalScreen'));
    }

    public function dashboard()
    {
        return view('admin/dashboard');
    }

    public function profile()
    {  
        if(!Auth::check()){
             return redirect('/');
        }
        $userdata = User::where('id','=',Auth::user()->id)->first();
        
        return view('admin/profile',compact('userdata'));
    }

    public function profileupdate(Request $request)
    {   
        $this->validate($request, [
            'oldpassword'           => 'required',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);
        $data  = $request->all();
        $datavalue = array(
          'password'  => bcrypt($data['password'])
        );
        $oldpasswords   = $data['oldpassword'];
        $matchpassword  = User::find($data['id'])->password;
        if(\Hash::check($oldpasswords, $matchpassword))
        {
            $check = 0;
            $check = User::where('id',$data['id'])->update($datavalue);
            if($check>0)
            {
              \Session::flash('message', 'Account Information Successfully Updated...');
              return \Redirect::to('/admin/profile');
            }
            else
            {
              
              return \Redirect::back()->with('message', 'Action Failed...Please Try Again!!!');
            }
        }
        else
        {
            return \Redirect::back()->with('message', 'Old Password Does Not Match...Please Try Again!!!');;
            
        }
        
    }

    public function screenlock($currtime,$id,$randnum)
    {
        Auth::logout();
        $user_record = User::where('id',$id)->first();
        return View('admin/screenlock')->with('currtime', $currtime)->with('user_record', $user_record)->with('randnum',$randnum);
    }

    public function errorCode404()
    {
     return view('errors.404');
    }



    public function errorCode405()
    {
      return view('errors.405');
    }

    
    
    
    public function job_list()
    {
        return view('admin/design1');
    }
    public function job()
    {
        return view('admin/job');
    }
    public function candidate_detail()
    {
        return view('admin/candidate_detail');
    }
}
