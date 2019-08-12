@extends('layouts.master')

@section('content')

@if(Auth::check())
  @php  
    $roles = Auth::user()->roles()->pluck('name')->implode(' ');
    $role_id = Auth::user()->roles()->pluck('id')->implode(' ');
    $modulePermission = Illuminate\Support\Facades\DB::table('role_has_permissions')->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')->where('permissions.parent_id','0')->where('role_id',$role_id)->select('permissions.id')->get()->pluck('id')->toArray();
    
    //print_r($myData);
  @endphp
@endif

<div class="container-fluid">
  <!-- Page-Title -->
  <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
        <div class="btn-group pull-right">
          <ol class="breadcrumb hide-phone p-0 m-0">
            
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
        <h4 class="page-title">Dashboard</h4>
      </div>
    </div>
  </div>
  <!-- end page title end breadcrumb -->

 
  @if(Auth::check())
    @php  
      $roles = Auth::user()->roles()->pluck('name')->implode(' ');
    @endphp
  @endif


<div class="row">
@if($roles=='Super Admin')
    <div class="col-md-6 col-xl-3">
      <div class="mini-stat clearfix bg-info"> <span class="mini-stat-icon bg-light"><i class="mdi mdi-cube-outline text-success"></i></span>
        <div class="mini-stat-info text-right text-light"> <span class="counter text-white">{{ App\User::where('IsActive','1')->get()->count() }}</span>
    <a href="" class="text-white">Active User </a>
         </div>
        
      </div>
    </div>
@endif

@if(in_array("1", $modulePermission))
<div class="col-md-6 col-xl-3">
      <div class="mini-stat clearfix bg-danger"> <span class="mini-stat-icon bg-light"><i class="mdi mdi-cube-outline text-success"></i></span>
        <div class="mini-stat-info text-right text-light"> <span class="counter text-white">{{ App\Client::where('IsActive','1')->get()->count() }}</span>
 <a href="{{ route('client.index') }}" class="text-white">Active Client </a>
         </div>
        
      </div>
    </div>
@endif

@if(in_array("2", $modulePermission))
    <div class="col-md-6 col-xl-3">
      <div class="mini-stat clearfix bg-warning"> <span class="mini-stat-icon bg-light"><i class="mdi mdi-cube-outline text-success"></i></span>
        <div class="mini-stat-info text-right text-light"> <span class="counter text-white">{{ App\Location::where('IsActive','1')->get()->count() }}</span>
 <a href="{{ route('location.index') }}" class="text-white">Active Location </a>
         </div>
        
      </div>
    </div>
@endif    
@if(in_array("3", $modulePermission))    
    <div class="col-md-6 col-xl-3">
      <div class="mini-stat clearfix bg-info"> <span class="mini-stat-icon bg-light"><i class="mdi mdi-cube-outline text-success"></i></span>
        <div class="mini-stat-info text-right text-light"> <span class="counter text-white">{{ App\Advertise::where('IsActive','1')->get()->count() }}</span>
 <a href="{{ route('advertise.index') }}" class="text-white">Active Advertisement </a>
         </div>
        
      </div>
    </div>
@endif

@if(in_array("4", $modulePermission))
     <div class="col-md-6 col-xl-3">
      <div class="mini-stat clearfix bg-warning"> <span class="mini-stat-icon bg-light"><i class="mdi mdi-cube-outline text-success"></i></span>
        <div class="mini-stat-info text-right text-light"> <span class="counter text-white">{{ App\Schedule::where('IsActive','1')->get()->count() }}</span>
 <a href="{{ route('schedule.index') }}" class="text-white">Advertisement Schedule </a>
         </div>
        
      </div>
    </div>
@endif    
    

  </div>


  </div>
  
  <!-- end row -->
</div>
@endsection 