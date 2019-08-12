<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Candidatemaster;
use Auth;
use App\Jobmaster;
use App\Organizationmaster;
use App\Applyjob;
use App\Keyword;
use App\Skill;
use App\Experience;
use App\Review;
use App\User;
use Mail;
use App\InterviewSchedule;
use App\Mail\CandidateApply;
use App\Mail\InterviewScheduleCandidateMail;
use App\Mail\InterviewScheduleEmployeeMail;
use App\Mail\SimpleMail;
use App\Activity\UpdateActivity;
use App\Activity\PdfParser;
use App\Activity\DocxConversion;
use App\Websitesetting;
use Illuminate\Support\Facades\DB;


class StudentController extends Controller
{
    public function studenthome($key=null)
    { 
      return redirect('/login');
      if($key){
      $org = Organizationmaster::where('orgSlug',$key)->first();
      if(!empty($org)){
        $jobmasterall = Jobmaster::where('IsActive','1')->where('OrgID', $org->id)->orderBy('id','DESC')->get();
        return view('student.home',compact('jobmasterall'));
      }else{
          //echo "string";
           return redirect('/error/pageNotFound');
      }
        }else{
            $jobmasterall = Jobmaster::where('IsActive','1')->orderBy('id','DESC')->get();
            return view('student.home',compact('jobmasterall'));
        }
      
    }
    public function studentlogin()
    {
      return view('student.studentlogin');
    }

    public function studentregister()
    {
      return view('student.studentregister');
    }

    public function studentprofile($id=false,$keyword)
    {
//       echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//        die;
      $jobmasterall = Jobmaster::where('IsActive','1')->where('id', $id)->first();
      return view('student.studentprofile',compact('id','jobmasterall'));
    }


 public function jobApply($orgName,$jobTitle)
    {
//       echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      $organization = Organizationmaster::where('orgSlug',$orgName)->first();
      if(empty($organization)){
          return redirect('error/pageNotFound');
      }else{
          $jobmasterall = Jobmaster::
        where('IsActive','1')
        ->where('OrgID',$organization->id)
        ->where('jobSlug',$jobTitle)
        ->first();

        if(empty($jobmasterall)){
          return redirect('error/pageNotFound');
        }else{
          return view('student.studentprofile',compact('id','jobmasterall'));
        }
      }
      
    }

    public function  profileupd(Request $request)
    { 
     
       
   
    $this->validate($request, [
        'FirstName' => 'required',
        'LastName' => 'required',
        'Email' => 'required|email',
        'Phone' => 'required|numeric|digits:10',
        'fileName' => 'required',
    ]);

    if (false)
    {

    }else{
        $data = $request->all();
        $cvfile = null;
        $response = array();

     $orgid =Jobmaster::select('id','OrgID')->find($data['jobid']);
            
      $valArray = array(
        
        'OrgID'               => $orgid->OrgID,
        'JobID'               => $data['jobid'],
        'FirstName'           => $data['FirstName'],
        'LastName'            => $data['LastName'],
        'Email'               => $data['Email'],
        'AlternateEmail'      => $data['AlternateEmail'],
        'Phone'               => $data['Phone'],
        'AlternatePhone'      => $data['AlternatePhone'],
        'UploadedCVPath'      => $data['fileName'],
        'CreatedBy'           => '0'
        
        ); 
      
      $studentinfo = Candidatemaster::create($valArray);
       
      if($studentinfo)
        {
        
        /////////////// mail////////////////
          
        $jobId = $data['jobid'];
        $jobmaster = Jobmaster::with('user')->with('org')->where('jobmasters.id',$data['jobid'])->first();
        $getWebInfo = Websitesetting::select('website_name', 'website_logo', 'email', 'address', 'mobile')->first();
        $content = [
        'title'         => 'Your Application for an '.$jobmaster->JobTitle.' position with '.$jobmaster->org->OrgName, 
        'body'          => 'The body of your message.',
                'address'       => $getWebInfo->address,
                'mobile'        => $getWebInfo->mobile,
                'website_name'  => $getWebInfo->website_name,
                'website_logo'  => $getWebInfo->website_logo,
                'email'         => $getWebInfo->email,
                'jobmaster'     => $jobmaster,
                'name'          => $data['FirstName'],
        ];
      //$receiverAddress = array('manish@nrt.co.in');
      $receiverAddress = array($data['Email']);
//
        //return view('emails.CandidateApply',compact('content'));
      $mail = Mail::to($receiverAddress)->bcc('manish@nrt.co.in')->send(new CandidateApply($content) );
            if (count(Mail::failures()) > 0) {

            echo "There was one or more failures. They were: <br />";

            foreach (Mail::failures as $email_address) {
                echo " - $email_address <br />";
            }
        } else {
            echo "No errors, all sent successfully!";
        }
        /////////////// mail////////////////
          
          
          
            //return \Redirect::back()->with('message','Apply Job Successfully.');
            return \Redirect::to('/'.$jobmaster->org->orgSlug)->with('message','Apply Job Successfully');
        }else{
            return \Redirect::back()->with('message','Action Failed...');
            //return \Redirect::back()->with('message','Action Failed...');
        }
    }



     // // print_r($data);
      
     //  if(!empty($data['UploadedCVPath']))
     //        { 
              
     //          $image_file         = $request->file('UploadedCVPath');
     //          $destinationPath    = 'assets/assets/img/pages/';
     //          $image_name         = $image_file->getClientOriginalName();
     //          $image              = value(function() use ($image_file)
     //              {
     //                $filename = time().'.'. $image_file->getClientOriginalExtension();
     //                  return strtolower($filename);
     //              });
     //        $request->file('UploadedCVPath')->move($destinationPath, $image);
           
     //        $cvfile = $image;
     //        }
           


    }
  
    public function create($id=false,$keyword)
    {  
      $jobmasterall = Jobmaster::find($id);
      $keyword = Keyword::select('id','name')->get();
      $skill = Skill::select('id','name')->get();
      return view('applylist.create',compact('jobmasterall','keyword','skill'));
    }

    public function store(Request $request)
    {   
        $data = $request->all();
        $this->validate($request,[
      
        'FirstName'            =>'required',
        'LastName'             =>'required',
        'Email'                =>'required',
        'AlternateEmail'       =>'required',
        'Phone'                =>'required',
        'AlternatePhone'       =>'required',
        'Source'               =>'required',
        'CurrentCTC'           =>'required',
        'NoticePeriod'         =>'required',
        'CurrentCompany'       =>'required',
        'CurrentDesignation'   =>'required',
        'TotalExperience'      =>'required',
        'Tags'                 =>'required',
        'Skills'               =>'required',
        'Status'               =>'required',
        'CVText'               =>'required',
        'CVKeywords'           =>'required',
        'UploadedCVPath'       =>'required'
        ]);
        if(!empty($data['UploadedCVPath']))
        { 
          
          $image_file         = $request->file('UploadedCVPath');
          $destinationPath    = 'assets/assets/img/pages/';
          $image_name         = $image_file->getClientOriginalName();
          $image              = value(function() use ($image_file)
              {
                $filename = time().'.'. $image_file->getClientOriginalExtension();
                  return strtolower($filename);
              });
          $request->file('UploadedCVPath')->move($destinationPath, $image);
          $cvfile = $image;
        }
           

        $add = Candidatemaster::create([
            'OrgID'                =>Auth::user()->OrgID,
            'JobID'                =>$data['JobID'],
            'FirstName'            =>$data['FirstName'],
            'LastName'             =>$data['LastName'],
            'Email'                =>$data['Email'],
            'AlternateEmail'       =>$data['AlternateEmail'],
            'Phone'                =>$data['Phone'],
            'AlternatePhone'       =>$data['AlternatePhone'],
            'Source'               =>$data['Source'],
            'CurrentCTC'           =>$data['CurrentCTC'],
            'NoticePeriod'         =>$data['NoticePeriod'],
            'CurrentCompany'       =>$data['CurrentCompany'],
            'CurrentDesignation'   =>$data['CurrentDesignation'],
            'TotalExperience'      =>$data['TotalExperience'],
            'Tags'                 =>$data['Tags'],
            'Skills'               =>$data['Skills'],
            'Status'               =>$data['Status'],
            'CVText'               =>$data['CVText'],
            'CVKeywords'           =>$data['CVKeywords'],
            'UploadedCVPath'       =>$cvfile,
            'CreatedBy'            =>Auth::user()->id,

          ]);
          if(isset($add)) {
                return \Redirect::back()->with('message','Candidate successfully added.');
          }else{
                return \Redirect::back()->with('message','Action Failed Please try again.');
          }
    
    }



    public function edit($id)
    {  
       $candidate = Candidatemaster::findOrFail($id);
       $keyword = Keyword::select('id','name')->get();
       $skill = Skill::select('id','name')->get();
       return view('applylist.edit',compact('candidate','keyword','skill'));
    }


    public function update(Request $request)
    {   
        $data = $request->all();
        $this->validate($request,[
        
        'FirstName'            =>'required',
        'LastName'             =>'required',
        'Email'                =>'required',
        'AlternateEmail'       =>'required',
        'Phone'                =>'required',
        'AlternatePhone'       =>'required',
        'Source'               =>'required',
        'CurrentCTC'           =>'required',
        'NoticePeriod'         =>'required',
        'CurrentCompany'       =>'required',
        'CurrentDesignation'   =>'required',
        'TotalExperience'      =>'required',
        'Tags'                 =>'required',
        'Skills'               =>'required',
        'Status'               =>'required',
        'CVText'               =>'required',
        'CVKeywords'           =>'required'
        
        ]);
        if(!empty($data['UploadedCVPath']))
        { 
          
          $image_file         = $request->file('UploadedCVPath');
          $destinationPath    = 'assets/assets/img/pages/';
          $image_name         = $image_file->getClientOriginalName();
          $image              = value(function() use ($image_file)
              {
                $filename = time().'.'. $image_file->getClientOriginalExtension();
                  return strtolower($filename);
              });
          $request->file('UploadedCVPath')->move($destinationPath, $image);
          $cvfile = $image;
        }else{
          $cvfile = $request->fileid;
        }
           

        $update = array(
            'OrgID'                =>Auth::user()->OrgID,
            'FirstName'            =>$data['FirstName'],
            'LastName'             =>$data['LastName'],
            'Email'                =>$data['Email'],
            'AlternateEmail'       =>$data['AlternateEmail'],
            'Phone'                =>$data['Phone'],
            'AlternatePhone'       =>$data['AlternatePhone'],
            'Source'               =>$data['Source'],
            'CurrentCTC'           =>$data['CurrentCTC'],
            'NoticePeriod'         =>$data['NoticePeriod'],
            'CurrentCompany'       =>$data['CurrentCompany'],
            'CurrentDesignation'   =>$data['CurrentDesignation'],
            'TotalExperience'      =>$data['TotalExperience'],
            'Tags'                 =>$data['Tags'],
            'Skills'               =>$data['Skills'],
            'Status'               =>$data['Status'],
            'CVText'               =>$data['CVText'],
            'CVKeywords'           =>$data['CVKeywords'],
            'UploadedCVPath'       =>$cvfile,
            'CreatedBy'            =>Auth::user()->id,

          );
        $datavalue = Candidatemaster::where('id',$data['id'])->update($update);
          if(isset($datavalue)) {
          return \Redirect::back()->with('message','Candidate successfully Updated.');
          }else{
              return \Redirect::back()->with('message','Action Failed Please try again.');
          }
    }
    
    
    
    
    public function addCandidate(Request $request)
    { 
    $data = $request->all();
    if(isset($data['fileName'])){
      $cvfile = $data['fileName']?$data['fileName']:null;
    }
    $response = array();
       
    if($data['candidate_id'] == ""){
           $v = \Validator::make($request->all(), [
        'name0' => 'required',
        'name1' => 'required',
        'email0' => 'required|email',
        'mobile0' => 'required|numeric|digits:10',
        'location' => 'required',
        'notic_period' => 'required',
        'ctc' => 'required',
        'month' => 'required',
        'year' => 'required',
        'current_cumpany' => 'required',
        'designation' => 'required',
    ]);

       }
       if($data['candidate_id'] != ""){
        $id = $data['candidate_id'];
           $v = \Validator::make($request->all(), [
        'name0' => 'required',
        'name1' => 'required',
        'email0' => 'required|email',
        'mobile0' => 'required|numeric|digits:10',
        'location' => 'required',
    ]);

       }
       
    
    if ($v->fails())
    {
        
        $response = array('result'=>'failed','message'=>"error",'data'=>$v->errors());
        echo json_encode($response);
        die;

    }else{
       
      if(!empty($data['UploadedCVPath']))
      { 

        $image_file         = $request->file('UploadedCVPath');
        $destinationPath    = 'assets/assets/img/pages/';
        $image_name         = $image_file->getClientOriginalName();
        $image              = value(function() use ($image_file)
            {
              $filename = time().'.'. $image_file->getClientOriginalExtension();
                return strtolower($filename);
            });
      $request->file('UploadedCVPath')->move($destinationPath, $image);

      $cvfile = $image;
      }
           

      $orgid =Jobmaster::select('id','OrgID')->find($data['jobid']);
           
      $valArray = array(
            'FirstName'            =>$data['name0'],
            'LastName'             =>$data['name1'],
            'Email'                =>$data['email0'],
            'AlternateEmail'       =>$data['email1'],
            'Phone'                =>$data['mobile0'],
            'AlternatePhone'       =>$data['mobile1'],
            'Source' => isset($data['source_type'])?$data['source_type']:null,
            'Location'             =>$data['location'],
            'Status'               =>$data['notes'],
            'Skills'=>isset($data['skill'])?implode(',', $data['skill']):null ,
            'CVKeywords'=>isset($data['keyword'])?implode(',', $data['keyword']):null,
            
      	); 
    $valArray['Tags'] = isset($data['Tags'])?$data['Tags']:null;
       
       //echo json_encode($valArray);

       if($data['candidate_id'] == ''){

          
          $valArray['CreatedBy']  = Auth::user()->id;
          $valArray['OrgID']      = Auth::user()->OrgID;
          $valArray['JobID']      = $data['jobid'];
          $valArray['UploadedCVPath'] = $cvfile;
          $candidate_id = Candidatemaster::create($valArray)->id;
           
        $exp = array(
           'CurrentCTC'           =>$data['ctc'],
           'NoticePeriod'         =>$data['notic_period'],
            'CurrentCompany'      =>$data['current_cumpany'],
            'CurrentDesignation'  =>$data['designation'],
            'TotalExperience'     =>$data['year'].' Year '.$data['month'].' Month ',
            'year'                =>$data['year'],
            'month'               =>$data['month'],
            'OrgID'                =>Auth::user()->OrgID,
            
        );
      
        $exp['CandidateId'] = $candidate_id;
        $exp['CreatedBy'] = Auth::user()->id;
        $studentinfo = Experience::create($exp);
           if ($studentinfo) {
                    $response = array('result' => 'success', 'message' => "Candidate Add Successfully", 'data' => $valArray);
                    echo json_encode($response);
                    die;
                } else {
                    $response = array('result' => 'success', 'message' => "Candidate Add Failed", 'data' => $valArray);
                    echo json_encode($response);
                    die;
                }
             
       }
       if($data['candidate_id'] != ''){
            $valArray['UpdatedBy'] = Auth::user()->id;
           
            $studentinfo = Candidatemaster::where('id',$data['candidate_id'])->update($valArray);
              if ($studentinfo) {
                    $response = array('result' => 'success', 'message' => "Candidate Update Successfully", 'data' => $valArray);
                    echo json_encode($response);
                    die;
                } else {
                    $response = array('result' => 'success', 'message' => "Candidate Update Failed", 'data' => $valArray);
                    echo json_encode($response);
                    die;
                }
            
       }      
      
      
       
      
    }
    
    
    
    
    
    

    }
    
    
    
    
      public function  addExperience(Request $request)
    { 
    $data = $request->all();
    $cvfile = null;
    $response = array();
       
    if($data['exprience_id'] == ""){
           $v = \Validator::make($request->all(), [
        'designation' => 'required',
        'current_company' => 'required',
        'ctc' => 'required|numeric',
        'notice_period' => 'required',
        'year' => 'required',
        'month' => 'required',
        'description' => 'required',
    
    ]);

       }
       if($data['exprience_id'] != ""){
        $id = $data['exprience_id'];
           $v = \Validator::make($request->all(), [
        'designation' => 'required',
        'current_company' => 'required',
        'ctc' => 'required|numeric',
        'notice_period' => 'required',
        'year' => 'required',
        'month' => 'required',
        'description' => 'required',
    ]);
       }
    if ($v->fails())
    {
        
        $response = array('result'=>'failed','message'=>"error",'data'=>$v->errors());
        echo json_encode($response);
        die;

    }else{
       
//        $response = array('result'=>'seccess','message'=>"add ");
//        echo json_encode($response);
//        die;

        $candidateId = Candidatemaster::select('id','OrgID')->find($data['candidate_id']);
           
      $exp = array(
            'CandidateId'             =>$data['candidate_id'],
            'CurrentCTC'              =>$data['ctc'],
            'NoticePeriod'            =>$data['notice_period'],
            'CurrentCompany'          =>$data['current_company'],
            'CurrentDesignation'      =>$data['designation'],
            'month'                   =>$data['month'],
            'year'                    =>$data['year'],
            'TotalExperience'         =>$data['year'].' Year'.$data['month'].' Month',
            'Description'             =>$data['description'],
      	); 
    
       
      
       //echo json_encode($valArray);

       if($data['exprience_id'] == ''){
           $exp['CreatedBy'] = Auth::user()->id;
           $exp['OrgID']     = Auth::user()->OrgID;
           //$candidate_id = Candidatemaster::create($valArray)->id;
            $expinfo = Experience::create($exp);
           if ($expinfo) {
                    $response = array('result' => 'success', 'message' => "Experience Add Successfully", 'data' => $exp);
                    echo json_encode($response);
                    die;
                } else {
                    $response = array('result' => 'success', 'message' => "Experience Add Failed", 'data' => $exp);
                    echo json_encode($response);
                    die;
                }
             
       }
       if($data['exprience_id'] != ''){
            $exp['UpdatedBy'] = Auth::user()->id;
           
            $expinfo = Experience::where('id',$data['exprience_id'])->update($exp);
              if ($expinfo) {
                    $response = array('result' => 'success', 'message' => "Experience Update Successfully", 'data' => $exp);
                    echo json_encode($response);
                    die;
                } else {
                    $response = array('result' => 'success', 'message' => "Experience Update Failed", 'data' => $exp);
                    echo json_encode($response);
                    die;
                }
            
       }      
      
      
       
      
    }
    
    
    
    
    
    

    }

    
    public function  addReview(Request $request)
    { 
        
      $data = $request->all();
      $response = array();
      if($data['review_id'] == ""){
          $v = \Validator::make($request->all(), ['Reviews' => 'required',]);
      }
      if($data['review_id'] != ""){
          $id = $data['review_id'];
          $v = \Validator::make($request->all(), ['Reviews' => 'required',]);
      }
      if ($v->fails())
      {
          $response = array('result'=>'failed','message'=>"error",'data'=>$v->errors());
          echo json_encode($response);
          die;
      }else{
                    
            
       
        
      $candidateId = Candidatemaster::select('id','OrgID')->find($data['candidate_id']);
      $update = new UpdateActivity();
      $updateFlag = $update->updateActivity($data['candidate_id'],'New review Update Review');
      $review = array(
            'Reviews'              =>$data['Reviews'],
      	); 
    
       
      
       //echo json_encode($valArray);

      if($data['review_id'] == ''){
           $review['CreatedBy']    = Auth::user()->id;
           $review['CandidateId']  = $data['candidate_id'];
           $review['OrgID']        = $data['OrgID'];
           $review['JobID']        = $data['JobID'];
           //$candidate_id = Candidatemaster::create($valArray)->id;
            $reviewinfo = Review::create($review);
           if ($reviewinfo) {
                    $response = array('result' => 'success', 'message' => "Review Add Successfully", 'data' => $review);
                    echo json_encode($response);
                    die;
                } else {
                    $response = array('result' => 'success', 'message' => "Review Add Failed", 'data' => $review);
                    echo json_encode($response);
                    die;
                }
             
      }
      if($data['review_id'] != ''){
            $review['UpdatedBy'] = Auth::user()->id;
           
            $reviewinfo = Review::where('id',$data['review_id'])->update($review);
              if ($reviewinfo) {
                    $response = array('result' => 'success', 'message' => "Review Update Successfully", 'data' => $review);
                    echo json_encode($response);
                    die;
                } else {
                    $response = array('result' => 'success', 'message' => "Review Update Failed", 'data' => $review);
                    echo json_encode($response);
                    die;
                }
            
       }      
      
      }
 }


    
    public function addInterview(Request $request)
    { 
        
    // interview shedule mail
   $data = $request->all();
    $response = array();
    

    if($data['interview_id'] == ""){
        $v = \Validator::make($request->all(), [
            'user_id'              =>'required|integer',
            'interview_date'       =>'required',
            'interview_time'       =>'required'
            ]);
    }
    if($data['interview_id'] != ""){
        $id = $data['interview_id'];
        $v = \Validator::make($request->all(), [
            'user_id'             =>'required|integer',
            'interview_date'       =>'required',
            'interview_time'       =>'required'
            ]);
    }
    if ($v->fails())
    {
        $response = array('result'=>'failed','message'=>"error",'data'=>$v->errors());
        echo json_encode($response);
        die;
    }else{
    

      $interview_date = date('Y-m-d', strtotime($data['interview_date']));
      $interview_time = $data['interview_time'];
      $interviwer = User::where('users.id',$data['user_id'])->first();
      $candidateId = Candidatemaster::find($data['candidate_id']);
     
      $interview = array(
              'user_id'             =>$data['user_id'],
              'interview_date'      =>$interview_date,
              'interview_time'      =>$interview_time,
              'Interview_type'      =>$data['interview_type'],
              'InterviewLocation'   =>$data['interviewLocation'],
      	); 
        
       if($data['interview_id'] == ''){
           $interview['created_by']    = Auth::user()->id;
           $interview['OrgID']    = Auth::user()->OrgID;
           $interview['jobid']    = $data['JobID'];
           $interview['status']    = '1';
           $interview['student_id']    = $data['candidate_id'];

           //transaction use
           
            $reviewinfo = InterviewSchedule::create($interview);
            Candidatemaster::where('id',$data['candidate_id'])->update(array('stageStatus'=>'4'));
         if ($reviewinfo) {
                /////////////// mail////////////////
        $jobmaster = Jobmaster::with('user')->with('org')->where('jobmasters.id',$data['JobID'])->first();
        $getWebInfo = Websitesetting::select('website_name', 'website_logo', 'email', 'address', 'mobile')->first();
        $content = [
    		'title'             => 'New interview with '.$jobmaster->JobTitle.' for '.$jobmaster->Location.' location', 
    		'emp_title'         => 'New interview with '.$candidateId->FirstName.' for '.$jobmaster->Location.' location', 
                'address'           => $getWebInfo->address,
                'mobile'            => $getWebInfo->mobile,
                'email'             => $getWebInfo->email,
                'website_name'      => $getWebInfo->website_name,
                'website_logo'      => $getWebInfo->website_logo,
                'jobmaster'         => $jobmaster,
                'interviwer'        => $interviwer,
                'candidate'         => $candidateId,
                'interview_date'    =>date('d-M, Y', strtotime($data['interview_date'])),
                'interview_time'    =>$data['interview_time'],
    		];

      $candidateMail = array('manish@nrt.co.in');
    	//$candidateMail = array($candidateId->Email);
    	$EmaployeeMail = array($interviwer->email);
    	//$EmaployeeMail = array('manish@nrt.co.in');
    	
       $from_name = "manish";
        $from_address="manish@nrt.co.in"; 
        $to_name="manish@nrt.co.in";
        $to_address="manish@nrt.co.in";
        $startTime = '2018-06-08';
        $endTime = '2018-06-08'; 
        $subject = 'test';
        $description= 'test';
        $location= 'location';
        //$result = $this->sendIcalEvent1($from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description, $location);
 
      //$content['body']=$result['message'] ;

      $mail = Mail::to($candidateMail)->bcc('manish@nrt.co.in')->send(new InterviewScheduleCandidateMail($content) );

    	$mail = Mail::to($EmaployeeMail)->bcc('manish@nrt.co.in')->send(new InterviewScheduleEmployeeMail($content) );
        
        /////////////// mail////////////////
               
               
                    /* Active update*/
                $update = new UpdateActivity();
                $msg = " Interview schedule on Date $interview_date and Time $interview_time";
                $updateFlag = $update->updateActivity($data['candidate_id'],$msg);
                $response = array('result' => 'success', 'message' => "Interview Add Successfully", 'data' => $interview);
                } else {
                   $response = array('result' => 'success', 'message' => "Interview Add Failed", 'data' => $interview);
                }
                echo json_encode($response);
                die;
             
       }
       if($data['interview_id'] != ''){

            //$interview['updated_at'] = Auth::user()->id;

            $update = new UpdateActivity();
            $msg = " Interview schedule Update on Date $interview_date and Time $interview_time";

            $updateFlag = $update->updateActivity($data['candidate_id'],$msg);
             
            $interviewinfo = InterviewSchedule::where('id',$data['interview_id'])->update($interview);
              if ($interviewinfo) {
                    $response = array('result' => 'success', 'message' => "Interview Update Successfully", 'data' => $interview);
                } else {
                    $response = array('result' => 'success', 'message' => "Interview Update Failed", 'data' => $interview);
                }
            
            echo json_encode($response);
            die;
       }      
      
      
       
      
    }
    
    
    
    
    
    

    }

    
    
    public function addEmail(Request $request) {

        // interview shedule mail
        $data = $request->all();
        $response = array();
        
       
        
       
//        $response = array('result' => 'success', 'message' => "send", 'data' => $data['emailTo']);
//            echo json_encode($response);
//            die;
        
        $v = \Validator::make($request->all(), [
                    'subject' => 'required',
                    'message' => 'required'
        ]);
        
        
        if ($v->fails()) {
            $response = array('result' => 'failed', 'message' => "error", 'data' => $v->errors());
            echo json_encode($response);
            die;
        } else {
            
            $candidateId = Candidatemaster::select('id', 'OrgID','JobID')->find($data['candidate_id']);

            // mail shoot
            
           
            
            $email = array(
                'CandidateId'       => $candidateId->id,
                'OrgID'             => $candidateId->OrgID,
                'JobID'             => $candidateId->JobID,
                'mail_to'           => $data['emailTo'],
                'mail_from'         => isset($data['emailFrom'])?$data['emailFrom']:null,
                'mail_cc'           => isset($data['cc'])?implode(',',$data['cc']):null,
                'mail_bcc'          => isset($data['bcc'])?implode(',',$data['bcc']):null,
                'subject'           => $data['subject'],
                'message'           => $data['message'],
                'Status'            => '0',
                'CreatedBy'        => Auth::user()->id,
            );
       
                $emailinfo = \App\Candidate_email::create($email);
                //Candidatemaster::where('id', $data['candidate_id'])->update(array('stageStatus' => '4'));
                if ($emailinfo) {
                   
                    
                    
                    
                                    /////////////// mail////////////////
                          $getWebInfo = Websitesetting::select('website_name', 'website_logo', 'email', 'address', 'mobile')->first();
                          $content = [
                                  'title'             => $data['subject'], 
                                  'body'              => $data['message'], 
                                  'address'           => $getWebInfo->address,
                                  'email'             => $getWebInfo->email,
                                  'mobile'            => $getWebInfo->mobile,
                                  'website_name'      => $getWebInfo->website_name,
                                  'website_logo'      => $getWebInfo->website_logo
                                  ];

                          //$receiverAddress = array('manish@nrt.co.in');
                          $candidateMail = array($data['emailTo']);
                          //$EmaployeeMail = array('manish@nrt.co.in');
                          $mail = Mail::to($candidateMail)->bcc('manish@nrt.co.in')->send(new SimpleMail($content) );

                          if (count(Mail::failures()) > 0) {
                              //echo "There was one or more failures. They were: <br />";
                              foreach (Mail::failures as $email_address) {
                                 // echo " - $email_address <br />";
                              }
                          } else {
                             // echo "No errors, all sent successfully!";
                          }
                          /////////////// mail////////////////
                    
                    
                    
                    
                    
                    
                   $update = new UpdateActivity();
                   $msg = " New Email Send...";
                   $updateFlag = $update->updateActivity($data['candidate_id'],$msg);
                    
                    $response = array('result' => 'success', 'message' => "Mail Send", 'data' => $email);
                    echo json_encode($response);
                    die;
                } else {
                    $response = array('result' => 'success', 'message' => "Mail Failed", 'data' => $email);
                    echo json_encode($response);
                    die;
                }
        }
    }
    
    public function addOffered(Request $request) {

        // interview shedule mail
        $data = $request->all();
        $response = array();
        $v = \Validator::make($request->all(), [
                    'offeredSalary' => 'required|integer',
                    'offeredDate' => 'required'
        ]);
        
        if ($v->fails()) {
            $response = array('result' => 'failed', 'message' => "error", 'data' => $v->errors());
            echo json_encode($response);
            die;
        } else {
           
             
            $candidateId = Candidatemaster::select('id', 'OrgID','JobID')->find($data['candidate_id']);
            $offerSalary = $data['offeredSalary'];
            $offerDate = date('Y-m-d', strtotime($data['offeredDate']));
            // mail shoot
           
            $offer = array(
                'OfferedSalary'         => $offerSalary,
                'OfferedJoiningDate'    => $offerDate,
                'stageStatus'           => '5'
            );
          
            $offerinfo = Candidatemaster::where('id', $data['candidate_id'])->update($offer);
            if($offerinfo){
                $update = new UpdateActivity();
                if($data['offerIsEdit'] == ''){
                  $msg = " Offered Salary - $offerSalary on date $offerDate";
                }else{
                  $msg = " Offered change : Offered Salary - $offerSalary on date $offerDate";
                }

                $updateFlag = $update->updateActivity($data['candidate_id'],$msg);
                $response = array('result' => 'success', 'message' => "Offer Operation Successfully", 'data' => $offer);
                  echo json_encode($response);
                die;
            } else {
                $response = array('result' => 'success', 'message' => "Offer Operation Failed", 'data' => $offer);
                echo json_encode($response);
                die;
            }
        }
    }
    public function addHire(Request $request) {

        // interview shedule mail
        $data = $request->all();
        $response = array();
        
        
        $v = \Validator::make($request->all(), [
            'joinOn' => 'required',
        ]);
        
        
        
        if ($v->fails()) {
            $response = array('result' => 'failed', 'message' => "error", 'data' => $v->errors());
            echo json_encode($response);
            die;
        } else {
           
             
            $candidateId = Candidatemaster::select('id', 'OrgID','JobID')->find($data['candidate_id']);
            $joinOn = date('Y-m-d', strtotime($data['joinOn']));
            // mail shoot
           
            $offer = array(
                'JoinOn'          => $joinOn,
                'stageStatus'     => '6'
            );
          
               $offerinfo = Candidatemaster::where('id', $data['candidate_id'])->update($offer);
                if ($offerinfo) {
                    $update = new UpdateActivity();
                    $msg = "Hiring process : Joining Date $joinOn";
                    $updateFlag = $update->updateActivity($data['candidate_id'],$msg);
                    $response = array('result' => 'success', 'message' => "Hiring Operation Successfully", 'data' => $offer);
                    echo json_encode($response);
                    die;
                } else {
                    $response = array('result' => 'success', 'message' => "Hiring Operation Failed", 'data' => $offer);
                    echo json_encode($response);
                    die;
                }
        }
    }


public function doUpload(Request $request){
$data = ($request->all());
$records = [];
 $status = '0';

   if(!empty($data['UploadedCVPath']))
            { 
              
              $image_file         = $request->file('UploadedCVPath');
              $destinationPath    = 'assets/assets/img/pages/';
              $image_name         = $image_file->getClientOriginalName();
              $extention          = $image_file->getClientOriginalExtension();

              $image              = value(function() use ($image_file)
                  {
                    $filename = time().'.'. $image_file->getClientOriginalExtension();
                      return strtolower($filename);
                  });
            $request->file('UploadedCVPath')->move($destinationPath, $image);
           
            $cvfile = $image;
            $target_file = $destinationPath.$image;
            $flag = false;
            $records['file_name'][] = $cvfile;
            $message = "file upload";
            $status = '1';

              if ($extention == 'pdf') {
                      //echo 'pdf';
                      $flag = true;
                      $pdfObj = new PdfParser();
                      //echo $target_file;
                      $resumeText = $pdfObj->parseFile($target_file);
                       //$resumeText = $pdfObj->getText();
                       //print_r($resumeText);
              }else if ($extention == 'docx') {
                      
                      
                      $flag = true;
                      $docObj = new DocxConversion($target_file);
                      $resumeText = $docObj->convertToText();
                      // print_r($resumeText);
                      // die;
              }else{
                  //echo 'file extention incorrect';
                  //die;
                  $flag = false;

                  // $docObj = new DocxConversion($target_file);
                  // $resumeText = $docObj->convertToText();
              }
           
              if($flag){
                          $fileInfo = explode(PHP_EOL, $resumeText);
                            foreach ($fileInfo as $k=>$row) {
                              $parts = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $row);
                              foreach ($parts as $part) {
                                if ($part == '') {
                                continue;
                              }
                                      $part = strtolower($part);
                                      //  ***************  EMAIL  **************
                             if (strpos($part, '@') || strpos($part, 'mail')) {
                                  $pattern = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
                                  preg_match_all($pattern, $part, $matches);
                                  foreach ($matches[0] as $match) {
                                      $records['email'][] = $match;
                                  }
                              }
                                 //  ***************  DOB  **************
                              if (preg_match('/dob|d.o.b|date of birth/', $part)) {
                                  $dob = preg_split('/:|-/', $part);
                                  foreach ($dob as $db) {
                                      $date = date_parse($db);
                                      if ($date['error_count'] == 0) {
                                          $records['dob'][] = $date['year'].'-'.$date['month'].'-'.$date['day'];
                                      }
                                  }
                              }


                              $p = '{.*?(\d\d?)[\\/\.\-]([\d]{2})[\\/\.\-]([\d]{4}).*}';
                              if (preg_match($p, $part)) {
                                  $date = preg_replace($p, '$3-$2-$1', $part);
                                  $dd = new \DateTime($date);
                                  $records['dob'][] = $dd->format('Y-m-d');
                              }

                                      // //  ***************  MOBILE  **************

                              preg_match_all('/\d{10}/', $part, $matches);
                              if (count($matches[0])) {
                                  foreach ($matches[0] as $mob) {
                                      $records['mobile'][] = $mob;
                                  }
                              }

                              preg_match_all('/\d{12}/', $part, $matches);
                              if (count($matches[0])) {
                                  foreach ($matches[0] as $mob) {
                                      $records['mobile'][] = $mob;
                                  }
                              }

                              preg_match_all('/(\d{5}) (\d{5})/', $part, $matches);
                              if (count($matches[0])) {
                                  foreach ($matches[0] as $mob) {
                                      $records['mobile'][] = $mob;
                                  }
                              }
               
                            if (isset($records['email'])) {
                                        foreach ($records['email'] as $email) {
                                            $e = explode('@', $email);
                                            $records['name'][] = $e[0];
                                        // code...
                                        }
                                    }
                                      

                            }
                        }


    echo json_encode(array('result'=>'success','status'=>$status,'messsage'=>'upload done','data'=>$records));
    die;
              }else{
    echo json_encode(array('result'=>'failed','status'=>$status,'messsage'=>'file format not match'));
    die;
              }

        //// parsing process end





            }else{
    echo json_encode(array('result'=>'failed','status'=>$status,'messsage'=>'file not select'));
    die;
            }
           




}



public function find_date($string)
{

    //Define month name:
    $month_names = [
        'january',
        'february',
        'march',
        'april',
        'may',
        'june',
        'july',
        'august',
        'september',
        'october',
        'november',
        'december',
    ];

    $month_number = $month = $matches_year = $year = $matches_month_number = $matches_month_word = $matches_day_number = '';

    //Match dates: 01/01/2012 or 30-12-11 or 1 2 1985
    preg_match('/([0-9]?[0-9])[\.\-\/ ]?([0-1]?[0-9])[\.\-\/ ]?([0-9]{2,4})/', $string, $matches);
    if ($matches) {
        if ($matches[1]) {
            $day = $matches[1];
        }

        if ($matches[2]) {
            $month = $matches[2];
        }

        if ($matches[3]) {
            $year = $matches[3];
        }
    }


    //Match month name:
    preg_match('/('.implode('|', $month_names).')/i', $string, $matches_month_word);

    if ($matches_month_word) {
        if ($matches_month_word[1]) {
            $month = array_search(strtolower($matches_month_word[1]), $month_names) + 1;
        }
    }

    //Match 5th 1st day:
    preg_match('/([0-9]?[0-9])(st|nd|th)/', $string, $matches_day);
    if ($matches_day) {
        if ($matches_day[1]) {
            $day = $matches_day[1];
        }
    }

    //Match Year if not already setted:
    if (empty($year)) {
        preg_match('/[0-9]{4}/', $string, $matches_year);
        if ($matches_year[0]) {
            $year = $matches_year[0];
        }
    }
    if (!empty($day) && !empty($month) && empty($year)) {
        preg_match('/[0-9]{2}/', $string, $matches_year);
        if ($matches_year[0]) {
            $year = $matches_year[0];
        }
    }

    //Leading 0
    if (1 == strlen($day)) {
        $day = '0'.$day;
    }

    //Leading 0
    if (1 == strlen($month)) {
        $month = '0'.$month;
    }

    //Check year:
    if (2 == strlen($year) && $year > 20) {
        $year = '19'.$year;
    } elseif (2 == strlen($year) && $year < 20) {
        $year = '20'.$year;
    }

    $date = [
        'year'    => $year,
        'month'   => $month,
        'day'     => $day,
    ];

    //Return false if nothing found:
    if (empty($year) && empty($month) && empty($day)) {
        return false;
    } else {
        return $date;
    }

}


public function resume(Request $request){
 $data = $request->all();

print_r( $data);
echo "<br>";

    $v = \Validator::make($request->all(), [
        'JobTitle' => 'required|unique:jobmasters_test,JobTitle,NULL,id,HiringManagerID,'.$data['HiringManagerID'],
        'HiringManagerID' => 'required',
        ]);
  
    
    if ($v->fails())
    {
        
        $response = array('result'=>'failed','message'=>"error",'data'=>$v->errors());
        echo json_encode($response);
        die;

    }else{
      echo "correct";

      DB::table('jobmasters_test')->insert(
    [
    'OrgID' => '1', 
    'JobTitle' => $data['JobTitle'],
    'HiringManagerID' => $data['HiringManagerID'],
    'IsActive'=>'1',
    'CreatedBy'=>'10',
    'Status'=>'1'
  ]
);
    }
 return redirect()->back();
 //print_r($data);
}



public function verify($id){

  $user_id = $id;
  $user = User::where('IsVerify','0')->find($id);

if( !empty($user)){
  echo "User Verify and update";  
  $flag = User::where('id',$user_id)->update(array('IsVerify'=>'1'));   
  if($flag){
    $msg = "Email Verify Sucessfully";
    $status = '1'; 
    //return redirect('admin/login');
  }else{
    $msg = "Email Verification Failed....";
    $status = '0';
  }
}else{
  

  $msg = "Invalid Link";
  $status = '0';
}

//$data['message'] = $msg;
//return view('student.verification',compact('data'));

  return redirect('verifyMessage')
  ->with('message',$msg)
  ->with('status',$status);
 //return \Redirect::back()->with('message','Apply Job Successfully.');
}
public function verifyMessage(){
  return view('student.verification');
}


function sendIcalEvent1($from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description, $location)
{
    $domain = 'exchangecore.com';

    //Create Email Headers
    $mime_boundary = MD5(TIME());

    $headers = "From: ".$from_name." <".$from_address.">\n";
    $headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
    $headers .= "Content-class: urn:content-classes:calendarmessage\n";
    
    //Create Email Body (HTML)
    $message = "$mime_boundary\r\n";
    $message .= "Content-Type: text/html; charset=UTF-8\n";
    $message .= "Content-Transfer-Encoding: 8bit\n\n";
    $message .= "<html>\n";
    $message .= "<body>\n";
    $message .= '<p>Dear '.$to_name.',</p>';
    $message .= '<p>'.$description.'</p>';
    $message .= "</body>\n";
    $message .= "</html>\n";
    $message .= "$mime_boundary\r\n";

    $ical = 'BEGIN:VCALENDAR' . "\r\n" .
    'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
    'VERSION:2.0' . "\r\n" .
    'METHOD:REQUEST' . "\r\n" .
    'BEGIN:VTIMEZONE' . "\r\n" .
    'TZID:Eastern Time' . "\r\n" .
    'BEGIN:STANDARD' . "\r\n" .
    'DTSTART:20091101T020000' . "\r\n" .
    'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
    'TZOFFSETFROM:-0400' . "\r\n" .
    'TZOFFSETTO:-0500' . "\r\n" .
    'TZNAME:EST' . "\r\n" .
    'END:STANDARD' . "\r\n" .
    'BEGIN:DAYLIGHT' . "\r\n" .
    'DTSTART:20090301T020000' . "\r\n" .
    'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
    'TZOFFSETFROM:-0500' . "\r\n" .
    'TZOFFSETTO:-0400' . "\r\n" .
    'TZNAME:EDST' . "\r\n" .
    'END:DAYLIGHT' . "\r\n" .
    'END:VTIMEZONE' . "\r\n" .  
    'BEGIN:VEVENT' . "\r\n" .
    'ORGANIZER;CN="'.$from_name.'":MAILTO:'.$from_address. "\r\n" .
    'ATTENDEE;CN="'.$to_name.'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'.$to_address. "\r\n" .
    'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
    'UID:'.date("Ymd\TGis", strtotime($startTime)).rand()."@".$domain."\r\n" .
    'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
    'DTSTART;TZID="Eastern Time":'.date("Ymd\THis", strtotime($startTime)). "\r\n" .
    'DTEND;TZID="Eastern Time":'.date("Ymd\THis", strtotime($endTime)). "\r\n" .
    'TRANSP:OPAQUE'. "\r\n" .
    'SEQUENCE:1'. "\r\n" .
    'SUMMARY:' . $subject . "\r\n" .
    'LOCATION:' . $location . "\r\n" .
    'CLASS:PUBLIC'. "\r\n" .
    'PRIORITY:5'. "\r\n" .
    'BEGIN:VALARM' . "\r\n" .
    'TRIGGER:-PT15M' . "\r\n" .
    'ACTION:DISPLAY' . "\r\n" .
    'DESCRIPTION:Reminder' . "\r\n" .
    'END:VALARM' . "\r\n" .
    'END:VEVENT'. "\r\n" .
    'END:VCALENDAR'. "\r\n";
    $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST'."\n";
    $message .= "Content-Transfer-Encoding: 8bit\n\n";
    $message .= $ical;

   
        //$mailsent = mail($to_address, $subject, $message, $headers);
        $result = array('header'=> $headers,'messsage'=> $message);
        return $result;
    }

public function errorCode404()
{
 return view('errors.frontError');
}

}
