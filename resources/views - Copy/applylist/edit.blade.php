@extends('layouts.master')

@section('title', '| Users')

@section('content')
<div class="container-fluid">
  <!-- Page-Title -->
  <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
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
          
           @if(Request::segment(3)==='edit')
           {{ Form::open(array('url' => 'admin/candidate-update','enctype'=>'multipart/form-data')) }}
             
            
            <?php  
                
                
                $JobID                   = $candidate->JobIDJobID;
                $FirstName               = $candidate->FirstName;
                $LastName                = $candidate->LastName;
                $Email                   = $candidate->Email;
                $AlternateEmail          = $candidate->AlternateEmail;
                $Phone                   = $candidate->Phone;
                $AlternatePhone          = $candidate->Phone;
                $Source                  = $candidate->Source;
                $CurrentCTC              = $candidate->CurrentCTC;
                $NoticePeriod            = $candidate->NoticePeriod;
                $CurrentCompany          = $candidate->CurrentCompany;
                $CurrentDesignation      = $candidate->CurrentDesignation;
                $TotalExperience         = $candidate->TotalExperience;
                $Tags                    = $candidate->Tags;
                $Skills                  = $candidate->Skills;
                $Status                  = $candidate->Status;
                $CVText                  = $candidate->CVText;
                $CVKeywords              = $candidate->CVKeywords;
                $UploadedCVPath          = $candidate->UploadedCVPath;
                
            ?>
            {!! Form::hidden('id',$candidate->id) !!}
            {!! Form::hidden('fileid',$candidate->UploadedCVPath) !!}
            
            @else
            
            <?php 
               
               
                $OrgID                   = '';
                $JobID                   = '';
                $FirstName               = '';
                $LastName                = '';
                $Email                   = '';
                $AlternateEmail          = '';
                $Phone                   = '';
                $AlternatePhone          = '';
                $Source                  = '';
                $CurrentCTC              = '';
                $NoticePeriod            = '';
                $CurrentCompany          = '';
                $CurrentDesignation      = '';
                $TotalExperience         = '';
                $Tags                    = '';
                $Skills                  = '';
                $Status                  = '';
                $CVText                  = '';
                $CVKeywords              = '';
                $UploadedCVPath          = '';


                             
            ?>
            @endif
            
           <div class="row"> 
           <div class="col-md-4">
            <div class="form-group">
             {{ Form::label('FirstName', 'First Name') }}
              <div>
                 
             {{ Form::text('FirstName', $FirstName, array('class' => 'form-control','placeholder'=>'First Name','required'=>'required')) }}
              </div>
            </div>
            </div>
          <div class="col-md-4">
            <div class="form-group">
             {{ Form::label('LastName', 'Last Name') }}
              <div>
                 
              {{ Form::text('LastName', $LastName, array('class' => 'form-control','placeholder'=>'Last Name','required'=>'required')) }}
              </div>
            </div>
            </div>
             <div class="col-md-4">
            <div class="form-group">
             {{ Form::label('Email', 'Email') }}
              <div>
                 
              {{ Form::email('Email', $Email, array('class' => 'form-control','placeholder'=>'Email','required'=>'required')) }}
              </div>
            </div>
            </div>
            </div>



           <div class="row"> 
           <div class="col-md-4">
            <div class="form-group">
             {{ Form::label('AlternateEmail', 'Alternate Email') }}
              <div>
                 
             {{ Form::email('AlternateEmail', $AlternateEmail, array('class' => 'form-control','placeholder'=>'AlternateEmail','required'=>'required')) }}
              </div>
            </div>
            </div>
          <div class="col-md-4">
            <div class="form-group">
             {{ Form::label('Phone', 'Phone') }}
              <div>
                 
              {{ Form::text('Phone', $Phone, array('class' => 'form-control','placeholder'=>'Phone','required'=>'required')) }}
              </div>
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
               {{ Form::label('AlternatePhone', 'AlternatePhone') }}
                <div>
                   
                {{ Form::text('AlternatePhone', $AlternatePhone, array('class' => 'form-control','placeholder'=>'AlternatePhone','required'=>'required')) }}
                </div>
              </div>
            </div>
            </div>



             <div class="row"> 
           <div class="col-md-4">
            <div class="form-group">
             {{ Form::label('Source', 'Source') }}
              <div>
                {!! Form::select('Source', ['Facebook' => 'Facebook', 'Freinds' => 'Freinds','Others'=>'Others'], $Source, array('class'=>'form-control','placeholder' => 'Select Source ','required'=>'required','style'=>'height:34px')) !!} 
            
              </div>
            </div>
            </div>
          <div class="col-md-4">
            <div class="form-group">
             {{ Form::label('CurrentCTC', 'CurrentCTC') }}
              <div>
                 
              {{ Form::text('CurrentCTC', $CurrentCTC, array('class' => 'form-control','placeholder'=>'Current CTC','required'=>'required')) }}
              </div>
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
               {{ Form::label('NoticePeriod', 'NoticePeriod') }}
                <div>
                   
                {{ Form::text('NoticePeriod', $NoticePeriod, array('class' => 'form-control','placeholder'=>'Notice Period','required'=>'required')) }}
                </div>
              </div>
            </div>
            </div>


          <div class="row"> 
           <div class="col-md-4">
            <div class="form-group">
             {{ Form::label('CurrentCompany', 'CurrentCompany') }}
              <div>
                 
             {{ Form::text('CurrentCompany', $CurrentCompany, array('class' => 'form-control','placeholder'=>'Current Company','required'=>'required')) }}
              </div>
            </div>
            </div>
          <div class="col-md-4">
            <div class="form-group">
             {{ Form::label('CurrentDesignation', 'CurrentDesignation') }}
              <div>
                 
              {{ Form::text('CurrentDesignation', $CurrentDesignation, array('class' => 'form-control','placeholder'=>'Current Designation','required'=>'required')) }}
              </div>
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
               {{ Form::label('TotalExperience', 'TotalExperience') }}
                <div>
                   
                {{ Form::text('TotalExperience', $TotalExperience, array('class' => 'form-control','placeholder'=>'TotalExperience','required'=>'required')) }}
                </div>
              </div>
            </div>
            </div>

             <div class="row"> 
           <div class="col-md-4">
            <div class="form-group">
             {{ Form::label('Tags', 'Tags') }}
              <div>
                 
             {{ Form::text('Tags', $Tags, array('class' => 'form-control','placeholder'=>'Tags','required'=>'required')) }}
              </div>
            </div>
            </div>
          <div class="col-md-4">
            <div class="form-group">
             {{ Form::label('Skills', 'Skills') }}
              <div>
               <select name="Skills" class="form-control selectpicker" data-live-search="true" required>
                 <option value="" selected disabled>Select Skills</option>
                 @foreach($skill as $rowskill)
                 <option value="{{$rowskill->id}}" @if($rowskill->id==$Skills) selected @endif>{{$rowskill->name}}</option>
                 @endforeach
             </select>     
              
              </div>
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
               {{ Form::label('Status', 'Status') }}
                <div>
                   
                {{ Form::text('Status', $Status, array('class' => 'form-control','placeholder'=>'Status','required'=>'required')) }}
                </div>
              </div>
            </div>
            </div>



              <div class="row"> 
           <div class="col-md-4">
            <div class="form-group">
             {{ Form::label('CVText', 'CVText') }}
              <div>
                 
             {{ Form::textarea('CVText', $CVText, array('class' => 'form-control','placeholder'=>'CVText','rows'=>'3','required'=>'required')) }}
              </div>
            </div>
            </div>
          <div class="col-md-4">
            <div class="form-group">
             {{ Form::label('CVKeywords', 'Keywods') }}
              <div>
                <select name="CVKeywords" class="form-control selectpicker" data-live-search="true" required>
                 <option value="" selected disabled>Select Keyword</option>
                 @foreach($keyword as $rowskeyword)
                 <option value="{{$rowskeyword->id}}" @if($rowskeyword->id==$Skills) selected @endif>{{$rowskeyword->name}}</option>
                 @endforeach
             </select>      
              
              </div>
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
               {{ Form::label('UploadedCVPath', 'UploadedCVPath') }}
                <div>
                 <input type="file" name="UploadedCVPath" class="form-control">  
                
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
    
    <!-- end col -->
  </div>
  <!-- end row -->
</div>
<!-- end container -->
@endsection
@section('extrajs')
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />

<script type="text/javascript">
  $(function() {
  $('.selectpicker').selectpicker();
});
</script>

@endsection