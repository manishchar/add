@extends('layouts.master')


@section('content')
<div class="container-fluid">
  <!-- Page-Title -->
  <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
        <div class="btn-group pull-right">
          <ol class="breadcrumb hide-phone p-0 m-0">
            <li class="breadcrumb-item"><a href="{{URL('/admin/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Keywords</li>
          </ol>
        </div>
        <h4 class="page-title"><i class="fa fa-key"></i>Keyword Available
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
          <h4 class="mt-0 header-title">Keyword List <a href="{!!URL('admin/keyword/create')!!}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>Add Keyword</a> </h4><hr/>
          <table class="table table-dark">
            <thead>
              <tr>
                <th width="10%">#</th>
               
                <th width="60%">Keyword Name</th>
                <th width="10%">Status</th>
               
                <th width="20%">Operations</th>
              </tr>
            </thead>
            <tbody>
            
            @php $count = 1; @endphp
            @if(isset($keywordall) && !empty($keywordall))
            @foreach ($keywordall as $keywordalls)
            <tr>
              <td>{{ $count }}</td>
             
              <td>{{ $keywordalls->name }}</td>
              <td>{{ $keywordalls->IsActive }}</td>
              
              <td><a href="{{ route('keyword.edit', $keywordalls->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a> {!! Form::open(['method' => 'DELETE', 'route' => ['keyword.destroy', $keywordalls->id] ]) !!}
                  
                     <?php
                   if($keywordalls->IsActive){ ?>
                <a href="{{ URL::to('admin/updateKeywords/'.$keywordalls->id.'/deactive') }}" class="btn btn-warning pull-left" style="margin-right: 3px;" onclick="return confirm('Are You Sure To Dctivated?')" title="Click to Deactive">Deactive</a>
                   <?php }else{ ?>
                <a href="{{ URL::to('admin/updateKeywords/'.$keywordalls->id.'/active') }}" class="btn btn-success pull-left" style="margin-right: 3px;" onclick="return confirm('Are You Sure To Activated?')" title="Click to Active">Aactive</a>
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