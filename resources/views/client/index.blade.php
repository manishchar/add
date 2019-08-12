@extends('layouts.master')


@section('content')

@if(Auth::check())
  @php  
    $roles = Auth::user()->roles()->pluck('name')->implode(' ');
    $role_id = Auth::user()->roles()->pluck('id')->implode(' ');
    $modulePermission = Illuminate\Support\Facades\DB::table('role_has_permissions')->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')->where('permissions.parent_id','1')->where('role_id',$role_id)->select('permissions.id')->get()->pluck('id')->toArray();
    
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
            <li class="breadcrumb-item active">Client</li>
          </ol>
        </div>
        <h4 class="page-title"><i class="fa fa-key"></i> Client Available
          
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
          <h4 class="mt-0 header-title">Client List &nbsp;&nbsp;&nbsp;
          @if(in_array("21", $modulePermission))
            <a href="{!!URL('admin/client/create')!!}" class="btn btn-primary"><i class="fa fa-plus"></i>Add Client</a>
      
            <a href="{{ URL('/admin/deactiveClient') }}" class="pull-right btn btn-danger">Deactive Clients</a>
          @endif
          </h4><hr/>
           @if(in_array("19", $modulePermission))
          <table id="myTable" class="table">
            <thead>
              <tr>
                <th width="2%">#</th>
               
                <th width="10%">Logo</th>
                <th width="10%">Company</th>
                <th width="10%">Name</th>
                <th width="10%">Email</th>
                <th width="5%">Mobile</th>
                <th width="20%">Address</th>
                <th width="2%">Front Status</th>
                <th width="2%">Available Slot</th>
               
                <th width="19%">Operations</th>
              </tr>
            </thead>
            <tbody>
            
            @php $count = 1; @endphp
            @if(isset($clients) && !empty($clients))
            @foreach ($clients as $client)
            <tr>
              <td>{{ $count }}</td>
             <td><img style="height: 40px;width: 50px;" src="{{URL('/assets/client/').'/'.$client->logo }}"></td>
             <td>{{ $client->company_name }}</td>
              <td>{{ $client->fname.' '.$client->lname }}</td>
              <td>{{ $client->email }}</td>
              <td>{{ $client->mobile }}</td>
              <td>{{ $client->address }}</td>
              <td>
                @if($client->IsActiveFront =='1')
                  <i class="text text-success fa fa-check"></i>
                @else
                  <i class="text text-danger fa fa-remove"></i>
                @endif
              </td>
              <td>
                {{$client->totalslots}}
              </td>
              
              <td>
                 @if(in_array("22", $modulePermission))
                <a href="{{ route('client.edit', $client->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;" title="Edit">
                  <i class="fa fa-edit"></i></a> 
                  @endif

                  {!! Form::open(['method' => 'DELETE', 'route' => ['client.destroy', $client->id] ]) !!}
                  
                <?php if($client->IsActive){ ?>
                <a href="{{ URL::to('admin/updateClient/'.$client->id.'/deactive') }}" class="btn btn-warning pull-left" style="margin-right: 3px;" onclick="return confirm('Are You Sure To Dctivated?')" title="Click to Deactive"><i class="fa fa-eye-slash"></i></a>
                   <?php }else{ ?>
                <a href="{{ URL::to('admin/updateClient/'.$client->id.'/active') }}" class="btn btn-success pull-left" style="margin-right: 3px;" onclick="return confirm('Are You Sure To Activated?')" title="Click to Active"><i class="fa fa-eye"></i></a>
                   <?php } ?>

                    <?php if($client->IsActiveFront){ ?>
                <a href="{{ URL::to('admin/updateClientFront/'.$client->id.'/deactive') }}" class="btn btn-danger pull-left" style="margin-right: 3px;" onclick="return confirm('Are You Sure To Dctivated Logo In Front?')" title="Deactive Logo"><i class="fa fa-eye-slash"></i></a>
                   <?php }else{ ?>
                <a href="{{ URL::to('admin/updateClientFront/'.$client->id.'/active') }}" class="btn btn-success pull-left" style="margin-right: 3px;" onclick="return confirm('Are You Sure To Activated Logo In Front?')" title="Active Logo"><i class="fa fa-eye"></i></a>

                   <?php } ?>
                              
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

