@extends('layouts.master')


@section('content')
<div class="container-fluid">
  <!-- Page-Title -->
  <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
        
        <h4 class="page-title"><i class="fa fa-key"></i>InterView Schedule
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
          
          
            {{ Form::open(array('url' => 'admin/interviewlist')) }}
            
            {{ Form::hidden('jobid', $jobid) }}
            {{ Form::hidden('candidate_id', $cid) }}
           <div class="row"> 
           
          <div class="col-md-12">
            <div class="form-group">
           
             {{ Form::label('user_id', 'Interviewr Name') }}
              <div>
                 
              <select name="user_id" class="form-control">
              
                <option value="" selected disabled>Select Interviewr Name</option>
              
                @foreach($user as $userrow)
                <option value="{{$userrow->id}}">{{$userrow->name}}</option>
                @endforeach
              </select>
              </div>
             
            </div>
            </div>
            </div> 
           <div class="row"> 
           <div class="col-md-6">
            <div class="form-group">
             {{ Form::label('interviewdate', 'Interview Date') }}
              <div>
                 
             {{ Form::date('interview_date', '', array('class' => 'form-control','placeholder'=>'YY-MM-DD','required'=>'required')) }}
              </div>
            </div>
            </div>
               <div class="col-md-6">
            <div class="form-group">
             {{ Form::label('interview_time', 'Interview Time') }}
              <div>
                 
              {{ Form::time('interview_time','', array('class' => 'form-control','placeholder'=>'Time','required'=>'required')) }}
              </div>
            </div>
            </div>
            </div>
            
           
            <div class="form-group m-b-0">
              <div>
                <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
                <button type="reset" class="btn btn-secondary waves-effect m-l-5"> Cancel </button>
              </div>
            </div>
         {!! Form::close() !!}
        </div>
      </div>
    </div>
   
  </div>
  <!-- end row -->
</div>
<!-- end container -->
@endsection