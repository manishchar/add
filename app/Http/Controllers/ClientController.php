<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use Auth;

use Mail;
use App\Mail\Client as clientMail;
use App\Websitesetting;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function __construct()
    {
       //$this->middleware(['clearance']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function myMail(){



$client = Client::findOrFail('1');

//$data = $request->all();
        $getWebInfo = Websitesetting::select('website_name', 'website_logo', 'email', 'address', 'mobile')->first();
         //$org = Organizationmaster::find($data['OrgID']);
         $content = [
             'title'         => 'Profile update notification with FirstAd', 
             'body'          => 'The body of your message.',
             'address'       => $getWebInfo->address,
             'mobile'        => $getWebInfo->mobile,
             'website_name'  => $getWebInfo->website_name,
             'website_logo'  => $getWebInfo->website_logo,
             'email'         => $getWebInfo->email,
             'client'       => $client,
             'name'          => $client->fname,
             'password_flag'          => false,
             ];
        $receiverAddress = array('manish09.chakravarti@gmail.com');
        //$receiverAddress = array($data['email']);
        // //return view('emails.CandidateApply',compact('content'));
        $mail = Mail::to($receiverAddress)->bcc('manish09.chakravarti@gmail.com')->send(new ClientMail($content) );
        if (count(Mail::failures()) > 0) {
            //echo "There was one or more failures. They were: <br />";
            foreach (Mail::failures as $email_address) {
              //  echo " - $email_address <br />";
            }
        }else {
            //echo "No errors, all sent successfully!";
        }

echo "success";
    }
    public function index()
    {
        $clients = Client::where('IsActive','1')->orderBy('id','DESC')->get();
        return view('client.index', compact('clients'));
    }
public function deactiveClient()
    {
        $clients = Client::where('IsActive','0')->orderBy('id','DESC')->get();
        return view('client.deactive', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('client.create');
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
            'fname'=>'required|max:100',
            'lname'=>'required',
            'mobile'=>'required',
            'email'=>'required|email|unique:clients'
            ]);

$path = 'assets/client/';        
$image_file         = $request->file('file');
$destinationPath    = $path;
$image_name         = $image_file->getClientOriginalName();
$extention          = $image_file->getClientOriginalExtension();
$image = value(function() use ($image_file){
$filename = time().'.'. $image_file->getClientOriginalExtension();
return strtolower($filename);
});
$request->file('file')->move($destinationPath, $image);
        $data = $request->all();
        $client =  Client::create([          
          'fname'                => $data['fname'],
          'lname'                => $data['lname'],
          'mobile'               => $data['mobile'],
          'email'                => $data['email'],
          'company_name'         => $data['company'],
          'totalslots'         => $data['totalslots'],
          'avilslots'         => $data['totalslots'],
          'logo'                =>  $image,
          'password'             => sha1('123456'),
          'address'              => $data['address'],
          'createdby'            =>Auth::user()->id,         
         ]);
       //dd($client->id);

        $getWebInfo = Websitesetting::select('website_name', 'website_logo', 'email', 'address', 'mobile')->first();
         //$org = Organizationmaster::find($data['OrgID']);
         $content = [
             'title'         => 'Registration with FirstAd', 
             'body'          => 'The body of your message.',
             'address'       => $getWebInfo->address,
             'mobile'        => $getWebInfo->mobile,
             'website_name'  => $getWebInfo->website_name,
             'website_logo'  => $getWebInfo->website_logo,
             'email'         => $getWebInfo->email,
             'client'       => $client,
             'name'          => $data['fname'].' '.$data['lname'],
             'password_flag'          => true,
             ];
        //$receiverAddress = array('manish@nrt.co.in');
        $receiverAddress = array($data['email']);
        // //return view('emails.CandidateApply',compact('content'));
        $mail = Mail::to($receiverAddress)->bcc('manish09.chakravarti@gmail.com')->send(new ClientMail($content) );
        if (count(Mail::failures()) > 0) {
            //echo "There was one or more failures. They were: <br />";
            foreach (Mail::failures as $email_address) {
              //  echo " - $email_address <br />";
            }
        }else {
            //echo "No errors, all sent successfully!";
        }


       if(isset($client)) {
        return redirect()->route('client.index')
            ->with('message',
             'Client successfully added.');
        }else{
            return redirect()->route('client.index')
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
        $client = Client::findOrFail($id);
        $keywordall = Client::all();
        return view('client.create', compact('client','keywordall'));
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
            'fname'=>'required|max:100',
        ]);
$path = 'assets/client/';        
$image_file         = $request->file('file');
$oldLogoUrl=$_POST['oldLogoUrl'];
$client = Client::findOrFail($id);
$data = $request->all();
if($image_file){
                // file selected
    if($oldLogoUrl != ''){
        if(file_exists($path.$oldLogoUrl)){
           if(unlink($path.$oldLogoUrl)){
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
    $client->logo   = $image;
}

        
        $client->fname = $request->input('fname');
        $client->lname = $request->input('lname');
        $client->email = $request->input('email');
        $client->mobile = $request->input('mobile');
        $client->company_name = $request->input('company');
        $client->address = $request->input('address');
        $client->totalslots = $request->input('totalslots');
        $client->avilslots = $request->input('totalslots');
       // $client->consumeslot = 0;
        /*
        if($_POST['consumeslot']==0)
        {
            
            $client->consumeslot = 0;
        }
        else
        {
            if($request->input('totalslots')>0)
            {
                $client->consumeslot = 0;
            }
           
        }*/
        //$client->avilslots = $request->input('totalslots');
        $upate = $client->save();


/////////////////

$getWebInfo = Websitesetting::select('website_name', 'website_logo', 'email', 'address', 'mobile')->first();
         //$org = Organizationmaster::find($data['OrgID']);
         $content = [
             'title'         => 'Profile update notification with FirstAd', 
             'body'          => 'The body of your message.',
             'address'       => $getWebInfo->address,
             'mobile'        => $getWebInfo->mobile,
             'website_name'  => $getWebInfo->website_name,
             'website_logo'  => $getWebInfo->website_logo,
             'email'         => $getWebInfo->email,
             'client'       => $client,
             'name'          => $data['fname'].' '.$data['lname'],
             'password_flag' => false,
             ];
        //$receiverAddress = array('manish09.chakravarti@gmail.com');
        $receiverAddress = array($data['email']);
        // //return view('emails.CandidateApply',compact('content'));
        $mail = Mail::to($receiverAddress)->bcc('manish09.chakravarti@gmail.com')->send(new ClientMail($content) );
        if (count(Mail::failures()) > 0) {
            //echo "There was one or more failures. They were: <br />";
            foreach (Mail::failures as $email_address) {
              //  echo " - $email_address <br />";
            }
        }else {
            //echo "No errors, all sent successfully!";
        }
///////////////////
        if(isset($upate)) {
        return redirect()->route('client.index')
            ->with('message',
             'Client successfully Updated.');
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
        $client = Client::findOrFail($id);
        $delete = $client->delete();

        if(isset($delete)) {
        return redirect()->route('client.index')
            ->with('message',
             'Client successfully Deleted.');
        }else{
            return redirect()->route('client.index')
            ->with('message',
             'Action Failed Please try again.');
        }
    }
    
    
    public function updateClientFront($id, $keyword){
        
        if($keyword == 'deactive'){
           
            $keywords = Client::findOrFail($id); 
            $updatedata = $keywords->fill(array('IsActiveFront'=>'0'))->save();
            return redirect('admin/client')->with('message',
             'Deactive Successfully.');
            
        }
        
        if($keyword == 'active'){
            $keywords = Client::findOrFail($id); 
            $updatedata = $keywords->fill(array('IsActiveFront'=>'1'))->save();
            return redirect('admin/client')->with('message',
             'Active Successfully.');
            
        }
    }
    public function updateClient($id, $keyword){
        
        if($keyword == 'deactive'){
           
            $keywords = Client::findOrFail($id); 
            $updatedata = $keywords->fill(array('IsActive'=>'0'))->save();
            return redirect('admin/client')->with('message',
             'Deactive Successfully.');
            
        }
        
        if($keyword == 'active'){
            $keywords = Client::findOrFail($id); 
            $updatedata = $keywords->fill(array('IsActive'=>'1'))->save();
            return redirect('admin/client')->with('message',
             'Active Successfully.');
            
        }
    }
}
