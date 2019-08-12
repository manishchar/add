<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Organizationmaster;
use Auth;
use Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
use Mail;
use App\Mail\Verification;
use App\Websitesetting;


class UserController extends Controller
{
    public function __construct() 
    {
        //$this->middleware(['auth','clearance']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   $rolename = Auth::user()->roles()->pluck('name')->implode(' '); 
        $users = User::where('CreatedBy',Auth::user()->id)->get();
        $roles = Role::where('userid','0')->where('name','!=',$rolename)->get();
        return view('users.index',compact('users','roles','organizationall'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // $roles = Role::get();
        $rolename = Auth::user()->roles()->pluck('name')->implode(' '); 
        $users = User::where('CreatedBy',Auth::user()->id)->get();
        $roles = Role::where('userid','0')->where('name','!=',$rolename)->get();
        //dd($roles);
        return view('users.create',compact('users','roles','organizationall'));
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
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed',
            'Phone'=>'required|max:15',
            'Designation'=>'required|max:50'
        ]);
        $data = $request->all();

        $usertype = $roles = $request['roles']; 
        /*if(isset($roles)){
           if($roles=='2') {
            $usertype = '2';
           }elseif($roles=='3') {
            $usertype = '3';
           }elseif($roles=='4') {
            $usertype = '4';
           }elseif($roles=='5') {
            $usertype = '5';
           }
        }
        */
        $user = User::create([
              'user_type' =>$usertype,
              'name' =>$data['name'],
              'email' =>$data['email'],
              'password' =>$data['password'],
              'Phone' =>$data['Phone'],
              'Designation' =>$data['Designation'],
              'CreatedBy' =>Auth::user()->id
            ]);

        

        if (isset($roles)) {
            $role_r = Role::where('id', '=', $roles)->firstOrFail();            
            $user->assignRole($role_r);
        }    
        // varification link


        //  $getWebInfo = Websitesetting::select('website_name', 'website_logo', 'email', 'address', 'mobile')->first();
        //  //$org = Organizationmaster::find($data['OrgID']);
        //  $content = [
        //      'title'         => 'Varification Link with Advertisement', 
        //      'body'          => 'The body of your message.',
        //      'address'       => $getWebInfo->address,
        //      'mobile'        => $getWebInfo->mobile,
        //      'website_name'  => $getWebInfo->website_name,
        //      'website_logo'  => $getWebInfo->website_logo,
        //      'email'         => $getWebInfo->email,
        //      'user_id'       =>$user->id,
        //      'name'          => $data['name'],
        //      ];
        // //$receiverAddress = array('manish@nrt.co.in');
        // $receiverAddress = array($data['email']);

        // // //return view('emails.CandidateApply',compact('content'));
        // $mail = Mail::to($receiverAddress)->bcc('manish09.chakravarti@gmail.com')->send(new Verification($content) );
        // if (count(Mail::failures()) > 0) {
        //     //echo "There was one or more failures. They were: <br />";
        //     foreach (Mail::failures as $email_address) {
        //       //  echo " - $email_address <br />";
        //     }
        // }else {
        //     //echo "No errors, all sent successfully!";
        // }

        /////////////    

        return redirect()->route('users.index')->with('message','User successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id); 
        $users = User::where('CreatedBy',Auth::user()->id)->get();
        $roles = Role::where('userid','0')->get();
        
        return view('users.create', compact('user', 'roles','users'));
    }
    public function change_password($id)
    {
        $user = User::findOrFail($id); 
        $users = User::where('CreatedBy',Auth::user()->id)->get();
        $roles = Role::where('userid','0')->get();
        return view('users.change_password', compact('user', 'roles','users','organizationall'));
    }
    public function update_password(Request $request)
    {
        $data = $request->all();
        $user = User::findOrFail($data['userId']); 
         $this->validate($request, [
            'password'=>'required|min:6|confirmed',
        ]);
        $input = $request->only(['password']);
        $user->fill($input)->save();
          return redirect()->route('users.index')
            ->with('message',
             'Password update successfully.');
        
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
        $user = User::findOrFail($id);
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id,
            //'password'=>'required|min:6|confirmed',
            'Phone'=>'required|max:15',
            'Designation'=>'required|max:50',

        ]);

        $input = $request->only(['email', 'name','Phone','Designation']);
        $roles = $request['roles'];
        $user->fill($input)->save();

        if (isset($roles)) {        
            $user->roles()->sync($roles);            
        }        
        else {
            $user->roles()->detach();
        }
        return redirect()->route('users.index')
            ->with('message',
             'User successfully edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->update(array('IsActive'=>'0'));

        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully deleted.');
    }

    public function deActiveUser($id)
    {
        $user = User::findOrFail($id);
        User::where('id',$id)->update(array('IsActive'=>'0'));
        return redirect()->route('users.index')
            ->with('message',
             'User successfully De-active.');
    }
    public function activeUser($id)
    {
        $user = User::findOrFail($id);
        User::where('id',$id)->update(array('IsActive'=>'1'));
        return redirect()->route('users.index')
            ->with('message',
             'User successfully Active.');
    }
}
