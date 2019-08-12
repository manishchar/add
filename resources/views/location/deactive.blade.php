@extends('layouts.master')


@section('content')

@if(Auth::check())
  @php  
    $roles = Auth::user()->roles()->pluck('name')->implode(' ');
    $role_id = Auth::user()->roles()->pluck('id')->implode(' ');
    $modulePermission = Illuminate\Support\Facades\DB::table('role_has_permissions')->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')->where('permissions.parent_id','2')->where('role_id',$role_id)->select('permissions.id')->get()->pluck('id')->toArray();
    
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
            <li class="breadcrumb-item active">Location</li>
          </ol>
        </div>
        <h4 class="page-title"><i class="fa fa-key"></i> Location Available
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
          <h4 class="mt-0 header-title">Location List &nbsp;&nbsp;&nbsp;
@if(in_array("15", $modulePermission))
            <a href="{!!URL('admin/location/create')!!}" class="btn btn-primary "><i class="fa fa-plus"></i>Add Location</a>
             <a href="{!!URL('admin/location')!!}" class="btn btn-success pull-right"><i class="fa fa-plus"></i>Active Location</a>
@endif
             </h4><hr/>
             @if(in_array("18", $modulePermission))
          <table id="myTable" class="table">
            <thead>
              <tr class="alert alert-primary">
                <th width="2%">#</th>
               
                <th width="10%">City</th>
                <th width="20%">Address</th>
                <th width="2%">Screen Id</th>
                <th width="2%">Screen Name</th>
                <th width="5%">Device id</th>
                <th width="2%">Type</th>
                <th width="2%">Size</th>
                <th width="5%">Lat</th>
                <th width="5%">Long</th>
                <th width="10%">Screen Status</th>
                <th width="10%">Status</th>
               
                <th width="20%">Operations</th>
              </tr>
            </thead>
            <tbody>
            
            @php $count = 1; @endphp
            @if(isset($locations) && !empty($locations))
            @foreach ($locations as $location)
            <tr>
              <td>{{ $count }}</td>
             
              <td>{{ $location->city->name }}</td>
              <td>{{ $location->location }}</td>
              <td>{{ $location->screen_id }}</td>
              <td>{{ $location->screen_name }}</td>
              <td>{{ $location->deviceId }}</td>
              <td>{{ $location->screenType->type }}</td>
              <td>{{ $location->screenSize->size }}</td>
              <td>{{ $location->lat }}</td>
              <td>{{ $location->lng }}</td>
              <td>
@php
$screenRecords = Illuminate\Support\Facades\DB::table('otherservices')->where('PIID',$location->deviceId)->first();
//dd($screenRecords);
  @$reboot=$screenRecords->reboot;
@endphp
@if(empty($screenRecords))
  <span class="badge badge-info">NA</span>
@else 
  @if($screenRecords->Status)
<span class="badge badge-success">ON</span>
  @else
<span class="badge badge-danger">OFF</span>
  @endif
  
@endif



              </td>
              <td>

               
 @if($location->IsActive =='1')
                        <i class="text text-success fa fa-check"></i>
                        @else
                        <i class="text text-danger fa fa-remove"></i>
                        @endif
              </td>
              
              <td>
                @if(in_array("16", $modulePermission))
                <a href="{{ route('location.edit', $location->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                @endif
                @if(in_array("17", $modulePermission))
                 {!! Form::open(['method' => 'DELETE', 'route' => ['location.destroy', $location->id] ]) !!}
                  
                     <?php
                   if($location->IsActive){ ?>
                <a href="{{ URL::to('admin/updateLocation/'.$location->id.'/deactive') }}" class="btn btn-warning pull-left" style="margin-right: 3px;" onclick="return confirm('Are You Sure To Dctivated?')" title="Click to Deactive">Deactive</a>
                   <?php }else{ ?>
                <a href="{{ URL::to('admin/updateLocation/'.$location->id.'/active') }}" class="btn btn-success pull-left" style="margin-right: 3px;" onclick="return confirm('Are You Sure To Activated?')" title="Click to Active">Aactive</a>
                   <?php } ?>
                 @endif   

                 <a href="{{ URL::to('admin/updateReboot/'.$location->deviceId) }}" class="btn btn-danger">Reboot</a>          
              </td>
            </tr>
            @php $count++; @endphp
            @endforeach
            @endif
            </tbody>
            
          </table>
          @endif
        </div>
      </div>
    </div>
    <!-- end col -->
  </div>
  <!-- end row -->
</div>
<!-- end container -->
@endsection


@section('extrajs')
{{-- <style type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"></style>
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
 --}}
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

<script type="text/javascript">
$(document).ready( function () {
    $('#myTable').DataTable({
      "lengthMenu": [[25, 50, 100, 500, 1000],[25, 50, 100, 500, "Max"]],
"pageLength": '500',
    });
} );

</script>

@endsection

