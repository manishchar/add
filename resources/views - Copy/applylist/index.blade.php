@extends('layouts.master')


@section('content')
<div class="container-fluid">
  <!-- Page-Title -->
  <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
        
        <h4 class="page-title"><i class="fa fa-key"></i>Available Candidate
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
          <h4 class="mt-0 header-title">People List
         
          </h4>
          <br/>
          <table class="table table-dark">
            <thead>
              <tr>
                <th>#</th>
                <th>JOB Name</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Resume</th>
                @can('Create Job Post')
                <th>Operation</th>
                @endcan
              </tr>
            </thead>
            <tbody>
            
           @php $count = 1; @endphp
           @if(!empty($candidatemaster))
           @foreach ($candidatemaster as $candidatemasters)
            <tr>
                <td>{{ $count }}</td>
                <td>{{ $candidatemasters->jobname->JobTitle }}</td> 
                <td>{{ $candidatemasters->FirstName }}</td> 
                <td>{{ $candidatemasters->LastName }}</td> 
                <td>{{ $candidatemasters->Email }}</td> 
                <td>{{ $candidatemasters->Phone }}</td>
                <td>{{ $candidatemasters->UploadedCVPath }}</td>
                @can('Create Job Post')
                <td>
                <a href="{{URL::to('admin/candidate/edit',array($candidatemasters->id))}}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                </td>
                @endcan 
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