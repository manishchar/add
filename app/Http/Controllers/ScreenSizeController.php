<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Screen_size;
use Auth;

use Mail;
use App\Mail\Client as clientMail;
use App\Websitesetting;

class ScreenSizeController extends Controller
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
        $screenSizes = Screen_size::orderBy('id','DESC')->get();
        return view('size.index', compact('screenSizes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('size.create');
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
            'size'=>'required|max:100',
            ]);

        $data = $request->all();
        $screen_size =  Screen_size::create([          
          'size'                => $data['size'],
         ]);
       if(isset($screen_size)) {
        return redirect()->route('size.index')
            ->with('message',
             'size successfully added.');
        }else{
            return redirect()->route('size.index')
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

        $client = Client::findOrFail($id);
        $client->fname = $request->input('fname');
        $client->lname = $request->input('lname');
        $client->email = $request->input('email');
        $client->mobile = $request->input('mobile');
        $client->address = $request->input('address');
        $upate = $client->save();

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
