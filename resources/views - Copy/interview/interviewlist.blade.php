@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap-rating.css') }}" rel="stylesheet">
<div class="container-fluid">
  <!-- Page-Title -->
  <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
        <div class="btn-group pull-right">
          <ol class="breadcrumb hide-phone p-0 m-0">
            <li class="breadcrumb-item"><a href="{{URL('/admin/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Interview List</li>
          </ol>
        </div>
        <h4 class="mt-0 header-title">Interview List </h4>
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

         <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr class="alert table-dark">
                <th width="5%">#</th>
               
                <th width="10%">Job Title</th>
                <th width="10%">Candidaate Name</th>
                <th width="10%">Contact No</th>
                <th width="10%">Scheduled By</th>
                <th width="10%">Interviewer Name</th>
                <th width="8%">Interview Date</th>
                <th width="8%">Interview Time</th>
                <th width="8%">Type</th>
                <th width="10%">Rating</th>
                <th width="5%">Feedback</th>
                <th width="4%">Status</th>
                <th width="10%">Operation</th>
               
              </tr>
            </thead>
            <tbody>
            
           
            @php $count = 1; @endphp
            @if(isset($interviewlist) && !empty($interviewlist))
            @foreach ($interviewlist as $interviewlists)
           
            <tr>
              <td>{{ $count }}</td>
              <td>
                @can('Create Job Post')
               <a href="{{URL('admin/job_detail').'/'.$interviewlists->jobid}}">{{ $interviewlists->jobs->JobTitle }}</a>
               @else
                {{ $interviewlists->jobs->JobTitle }}
               @endif
              </td>
              <td>
                   <a href="{{URL('admin/candidate_detail').'/'.encrypt($interviewlists->jobid).'/'.$interviewlists->student_id}}">{{ $interviewlists->student->FirstName.' '.$interviewlists->student->LastName }}</a>
                </td>
              <td>{{ $interviewlists->student->Phone }}</td>
              <td>{{ $interviewlists->userBy->name }}</td>
               <td>{{ $interviewlists->user->name }}</td>
              <td>
                
                  @php
                   echo date('d-M-Y',strtotime($interviewlists->interview_date)) 
                  @endphp
                </td>
              <td>@php
                 
                echo date("g:i A", strtotime($interviewlists->interview_time));
              @endphp
              </td>
               <td>{{ $interviewlists->Interview_type }}</td>
              <td>
               @if($interviewlists->rating)
                              <p class="title">
                                @for($i=0;$i< 5;$i++)
                                @if($interviewlists->rating > $i)
                                    <span class="ti-star active"></span>
                                @else
                                    <span class="ti-star"></span>
                                @endif
                                @endfor
                                </p>
                            @endif
              </td>
              <td>
                 @if($interviewlists->status==3)
                  {{ $interviewlists->reason }}
                 @else
                   {{ $interviewlists->feadback }}
                 @endif 
              </td>
              <td>
              
              @if($interviewlists->status==1) 
                  <a href="javascript:;" class="btn btn-warning">Pending</a>
              @elseif($interviewlists->status==2)
              <a href="javascript:;" class="btn btn-success">Done</a>
              @elseif($interviewlists->status==3)
              <a href="javascript:;" class="btn btn-danger">Cancel</a>
              @endif
              
              </td>
              <td>
                  @php $today = date('Y-m-d'); @endphp
                  @if($interviewlists->status==1 && ( Auth::user()->id == $interviewlists->user_id ) ) 
                  
                  
                  
                  @if( strtotime($interviewlists->interview_date) <= strtotime($today) )
                  <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-center{{ $count}}">Feadback</button>

                  <button type="button" class="btn btn-danger waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-center-cancel{{ $count}}">Canceled</button>

                  @endif
                   
                 
              
              
              @endif
               @php 
                   //echo strtotime($interviewlists->interview_date).' = '.strtotime($today);
                   //echo Auth::user()->id.' = '.$interviewlists->user_id;
                  @endphp
              </td>
            </tr>
            <div class="modal fade bs-example-modal-center{{ $count}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title mt-0">Candidate Feadback</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      </div>
                      <div class="modal-body">
                      {{ Form::model($interviewlists, array('route' => array('interviewlist.update', $interviewlists->id,'status=2'), 'method' => 'PUT')) }}
                        
                      <div class="p-4 text-center">
                          <h5 class="font-16 m-b-15">Rating</h5>
                          <input type="hidden" name="rating" class="rating-tooltip" data-filled="mdi mdi-star font-32 text-primary" data-empty="mdi mdi-star-outline font-32 text-muted"/>
                      </div>
                  
                       {{ Form::textarea('feadback', '', array('class'=>'form-control','rows'=>'2','placeholder'=>'Feadback','required'=>'required')) }} 
                       <br>
                      <button type="submit" class="btn btn-primary waves-effect waves-light pull-right"> Submit </button>
                      {{ Form::close() }}
                              
                      </div>
                  </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            
            <div class="modal fade bs-example-modal-center-cancel{{ $count}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title mt-0">Reason</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      </div>
                      <div class="modal-body">
                      {{ Form::model($interviewlists, array('route' => array('interviewlist.update', $interviewlists->id,'status=3'), 'method' => 'PUT')) }}
                        
                  
                       {{ Form::textarea('reason', '', array('class'=>'form-control','rows'=>'2','placeholder'=>'Reason','required'=>'required')) }} 
                       <br>
                      <button type="submit" class="btn btn-primary waves-effect waves-light pull-right"> Submit </button>
                      {{ Form::close() }}
                              
                      </div>
                  </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            
            @php $count++; @endphp
            @endforeach
            @endif
            
            </tbody>
            
          </table>
        </div>
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
<script src="{{ asset('assets/pages/bootstrap-rating.min.js') }}"></script>
<script src="{{ asset('assets/pages/rating-init.js') }}"></script>

<style>
    .active{
        color: red;
    }
</style>

@endsection