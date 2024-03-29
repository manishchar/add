@extends('layouts.master')


@section('content')
@if(Auth::check())
  @php  
    $roles = Auth::user()->roles()->pluck('name')->implode(' ');
    $role_id = Auth::user()->roles()->pluck('id')->implode(' ');
    $modulePermission = Illuminate\Support\Facades\DB::table('role_has_permissions')->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')->where('permissions.parent_id','3')->where('role_id',$role_id)->select('permissions.id')->get()->pluck('id')->toArray();
    
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
            <li class="breadcrumb-item"><a href="{{URL('/admin/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Advertisement</li>
          </ol>
        </div>
        <h4 class="page-title"><i class="fa fa-key"></i> Advertisement Available
          @php
            print_r($modulePermission);
          @endphp
        </h4>
      </div>
    </div>
  </div>
  @if (count($errors) > 0)
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif
  @if(Session::has('message'))
  <div class="alert alert-success login-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {!! Session::get('message') !!} </div>
  @endif
  <!-- end page title end breadcrumb -->
  <div class="row">

    <div class="col-lg-12">
      <div class="card m-b-30">
        <div class="card-body">
          <h4 class="mt-0 header-title">Advertisement List <a href="{!!URL('admin/advertise/create')!!}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>Add Advertisement</a> </h4><hr/>
          <table class="table table-dark">
            <thead>
              <tr>
                <th width="10%">#</th>
                <th width="60%">Client Name</th>
                <th width="60%">Location</th>
                <th width="60%">Name</th>
                <th width="10%">Status</th>
                <th width="20%">Operations</th>
              </tr>
            </thead>
            <tbody>
            
            @php $count = 1; @endphp
            @if(isset($advertise) && !empty($advertise))
            @foreach ($advertise as $key=>$value)
            <tr>
              <td>{{ $count }}</td>
             
              <td>{{ $value->client_id }}</td>
              <td>{{ $value->location_id }}</td>
              <td>{{ $value->advertise_name }}</td>
              <td>{{ $value->IsActive }}</td>
              
              <td><a href="{{ route('value.edit', $value->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a> {!! Form::open(['method' => 'DELETE', 'route' => ['location.destroy', $value->id] ]) !!}
                  
                     <?php
                   if($value->IsActive){ ?>
                <a href="{{ URL::to('admin/updateLocation/'.$value->id.'/deactive') }}" class="btn btn-warning pull-left" style="margin-right: 3px;" onclick="return confirm('Are You Sure To Dctivated?')" title="Click to Deactive">Deactive</a>
                   <?php }else{ ?>
                <a href="{{ URL::to('admin/updateLocation/'.$value->id.'/active') }}" class="btn btn-success pull-left" style="margin-right: 3px;" onclick="return confirm('Are You Sure To Activated?')" title="Click to Active">Aactive</a>
                   <?php } ?>
                              
              </td>
            </tr>
            @php $count++; @endphp
            @endforeach
            @endif
            </tbody>
            
          </table>
        </div>
      </div>
    </div>
    <!-- end col -->
  </div>
  <!-- end row -->
</div>
<!-- end container -->
@endsection