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
          <form method="post" action="{{URL('/admin/add_marquee')}}">
             {{csrf_field()}}
            <div class="col-md-6">
                <div class="form-group"> 
                   {{ Form::label('Content', 'Content') }}
                  <div>
<textarea name="content" id="content" placeholder="Content" class="form-control" required style="resize: none;height: 100px;"></textarea>
                    </div>
                </div>
              </div>
            <div class="col-md-4">
                <div class="form-group"> <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
              <button type="reset" class="btn btn-secondary waves-effect m-l-5"> Cancel </button>   </div>
                </div>
          </form>

          <hr/>
             @if(in_array("18", $modulePermission))
          <table id="myTable" class="table">
            <thead>
              <tr class="alert alert-primary">
                <th width="2%">#</th>
               
                <th width="20%">Content</th>  
                <th width="10%">Operations</th>
              </tr>
            </thead>
            <tbody>
            
            @php $count = 1; @endphp
            @if(isset($marqueelist) && !empty($marqueelist))
            @foreach ($marqueelist as $marquee)
            <tr>
              <td>{{ $count }}</td>
             
              <td>{{ $marquee->content }}</td>
              
              <td>
                
                <a onclick="return confirm('Do you really want to delete this content ?');" href="{{URL('/admin/marqueedelete/'.$marquee->id)}}" class="btn btn-info pull-left" style="margin-right: 3px;" title="Edit">
                  <i class="fa fa-trash"></i></a>
               {{--  @if(in_array("16", $modulePermission))
                <a href="{{ route('location.edit', $location->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                @endif
                @if(in_array("17", $modulePermission))
                 {!! Form::open(['method' => 'DELETE', 'route' => ['location.destroy', $location->id] ]) !!}
                  
                    
                 @endif   

                 <a href="{{ URL::to('admin/updateReboot/'.$location->deviceId) }}" class="btn btn-danger">Reboot</a> --}}          
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
    $('#myTable').DataTable();
} );

</script>

@endsection

