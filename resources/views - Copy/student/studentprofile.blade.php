@extends('layouts.frontmaster')

@section('content')

<section class="page-header lighten-4" style="background: url(assets/frontend/images/cover-1.jpg)">
	<div class="container">
	    <h2>Profile</h2>
	</div>
</section>
    <section>

      

        @if(Session::has('message'))
        <div class="alert alert-success login-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {!! Session::get('message') !!} </div>
      @endif
  <div class="container">
            
                <div class="row">
                    <div class="col-md-12 well">
                            <div class="row">
                                <label class="col-sm-3">Job Title</label>
                                <div class="col-sm-9"><strong>{!! $jobmasterall->JobTitle !!}</strong></div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3">Job Description</label>
                                <div class="col-sm-9">{!! $jobmasterall->Description !!}</div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3">Location</label>
                                <div class="col-sm-9">{!! $jobmasterall->Location !!}</div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3">Job Url </label>
                                <div class="col-sm-9">@php  echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];  @endphp</div>
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



<div class="file-upload">
  <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Add Resume</button>
<form id="resumeForm" enctype= "multipart/form" onsubmit="return resumeForm(this)">
   <input type="hidden"  name="_token" id="csrf-token" value="{{ Session::token() }}" />

  <div class="image-upload-wrap">
    <input class="file-upload-input" type="file" id="selectfile"  name="UploadedCVPath" onchange="fileChange(this)" />
    <div class="drag-text">
      <h3>Drop Your Resume here</h3>
    </div>
  </div>
</form>
</div>

<div class="col-sm-12 form-group">
                      <div class="text text-danger" id="errorMessage"></div>
                      <i id="show" class="text text-success fa fa-check" aria-hidden="true"> Success</i>
                      <i id="hide" class="text text-danger fa fa-times" aria-hidden="true"> Failed</i>
</div>


<br/>
<br/>
<br/>

                <!-- <form id="resumeForm" enctype= "multipart/form" onsubmit="return resumeForm(this)">

                                              <input type="hidden"  name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                              <div>
                                              <input type="file" class="form-control" id="selectfile"  name="UploadedCVPath" onchange="fileChange(this)">
                                              <div> 
                                            <label for="selectfile"
style="    position: relative;
    display: inherit;
    top: -83px;
    font-size: 25px;
    background: #eeeeef;
    line-height: 40px;
    text-align: center;margin: 0px 6px 0px 6px;" 
                                            >  
                                              Drop Your Resume here      
                                            </label>

                </form>
            
                <div class="col-sm-12 form-group">
                      <div class="text text-danger" id="errorMessage"></div>
                      <i id="show" class="text text-success fa fa-check" aria-hidden="true"> Success</i>
                      <i id="hide" class="text text-danger fa fa-times" aria-hidden="true"> Failed</i>
                </div> -->
          

            {!! Form::open(array('url' => 'profileupd','enctype'=>'multipart/form-data','id'=>'basicInformation')) !!}

            <div class="card">

                  <div class="card-body">
                      <div class="row">
                          <div class="col-md-12">

                              <div class="content-row">

                                  <h5 class="m-b-15">Basic Information</h5>

                                  <div class="form-group">

                                      <input type="hidden" name="fileName" id="file_name0" class="myForm">

                                      <input type="text" name="FirstName" id="name0" class="form-control myForm"  placeholder="Name" required="">

                                      <input type="hidden" name="jobid" value="{{ $jobmasterall->id }}">

                                  </div>

                                  <div class="form-group">

                                      <input type="text" name="LastName" class="form-control myForm"  placeholder="Last Name" required="">

                                  </div>

                              </div>

                              <div class="content-row">

                                        <h5 class="m-b-15">Contact Information</h5>

                                        <div class="row">

                                            

                                            <div class="col-md-6">

                                                <div class="input-group">

                                            <span class="input-group-addon">

                                                <i class="md-email"></i>

                                            </span>

                                                    <input type="email" id="email0" name="Email" class="form-control myForm"  placeholder="Email" required="">

                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="input-group">

                                            <span class="input-group-addon">

                                                <i class="md-email"></i>

                                            </span>

                                                     <input type="email" id="email1" name="AlternateEmail" class="form-control myForm"  placeholder="Alternate Email">

                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="input-group">

                                            <span class="input-group-addon">

                                                <i class="md-phone"></i>

                                            </span>

                                                    <input type="text" name="Phone" class="form-control myForm" id="mobile0"  placeholder="Phone Number" required="">

                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="input-group">

                                            <span class="input-group-addon">

                                                <i class="md-phone"></i>

                                            </span>

                                                    <input type="text" name="AlternatePhone" class="form-control myForm" id="mobile1" placeholder="Alternate Phone">

                                                </div>

                                            </div>

                                        </div>

                              </div>

                          </div>
                    </div>
                  </div>

               

                <div class="col-sm-4 col-sm-offset-4 col-xs-6 col-xs-offset-3">

                    <button type="submit" class="btn btn-primary btn-block btn-lg m-t-20">Apply</button>

                </div>
            </div>
            {!! Form::close() !!}
          
  </div> 
    </section>
@endsection




@section('extrajs') 

<style>



.file-upload {
  background-color: #ffffff;
  margin: 0 auto;
  padding: 20px;
}

.file-upload-btn {
  width: 100%;
  margin: 0;
  color: #fff;
  background: #1FB264;
  border: none;
  padding: 5px;
  border-radius: 4px;
  border-bottom: 4px solid #15824B;
  transition: all .2s ease;
  outline: none;
  text-transform: uppercase;
  font-weight: 700;
}

.file-upload-btn:hover {
  background: #1AA059;
  color: #ffffff;
  transition: all .2s ease;
  cursor: pointer;
}

.file-upload-btn:active {
  border: 0;
  transition: all .2s ease;
}

.file-upload-content {
  display: none;
  text-align: center;
}

.file-upload-input {
  position: absolute;
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
  outline: none;
  opacity: 0;
  cursor: pointer;
}

.image-upload-wrap {
  margin-top: 20px;
  border: 4px dashed #1FB264;
  position: relative;
}

.image-dropping,
.image-upload-wrap:hover {
  background-color: #1FB264;
  border: 4px dashed #ffffff;
}

.image-title-wrap {
  padding: 0 15px 15px 15px;
  color: #222;
}

.drag-text {
  text-align: center;
}

.drag-text h3 {
  font-weight: 20;
  text-transform: uppercase;
  color: #15824B;
  padding: 0px 0;
  display: inline-table;
}

.file-upload-image {
  max-height: 200px;
  max-width: 200px;
  margin: auto;
  padding: 20px;
}

.remove-image {
  width: 200px;
  margin: 0;
  color: #fff;
  background: #cd4535;
  border: none;
  padding: 10px;
  border-radius: 4px;
  border-bottom: 4px solid #b02818;
  transition: all .2s ease;
  outline: none;
  text-transform: uppercase;
  font-weight: 700;
}

.remove-image:hover {
  background: #c13b2a;
  color: #ffffff;
  transition: all .2s ease;
  cursor: pointer;
}

.remove-image:active {
  border: 0;
  transition: all .2s ease;
}

#errorMessage{
  display: none;
}
#selectfile{
   background-color: #EEE; 
      border: #999 5px dashed;
      width: 100%; 
      height: 100%;
      padding: 50px;
      font-size: 18px;
      position: relative;
}
  #drop_file_zone {
      background-color: #EEE; 
      border: #999 5px dashed;
      width: 50%; 
      height: 50%;
      padding: 8px;
      font-size: 18px;
      position: relative;
  }
  #drag_upload_file {
    width:50%;
    margin:0 auto;
  }
  #drag_upload_file p {
    text-align: center;
  }
  #drag_upload_file #selectfile {
    /*display: none;*/
    position: absolute;
    top: 0px;
    left: 0px;
    border: 1px solid red;
    height: 60%;
    width: 100%;
  }
</style>



<script type="text/javascript">
   
 var base_url = "{{  URL('/') }}";
  $('#show').hide();
  $('#hide').hide();

    function fileChange(obj){
    // alert('file select');
    $('#show').hide();
    $('#hide').hide();
    $('#errorMessage').html("");
    var formObj = $('#basicInformation').find('.myForm'); 
        $(formObj).each(function (){
        $(this).val('');
    });
var fileName, fileExtension;
fileName = $(obj).val();
fileExtension = fileName.substr((fileName.lastIndexOf('.') + 1));
//console.log (fileExtension);

  if(fileExtension == 'doc' || fileExtension=='docx' || fileExtension== 'pdf'){
    $('#resumeForm').submit();
  }else{
    $('#errorMessage').html('Invalid Format. Allow only Pdf or Docx file').fadeIn();
    $(obj).val('');
  }
    //$('#resumeForm').submit();
    }


function resumeForm(obj){
    var formData = new FormData(obj);
    //alert('form submit');
    $.ajax({
        type: "POST",
        url: base_url+"/doUpload",
        cache:false,
        contentType: false,
        processData: false,
         data:formData,
        beforeSend(xhr){
           // alert('before');
           $('#show').hide();
           $('#hide').hide();
               // $('#saveBtn').html('Loding.....');
               // $('#saveBtn').prop('disabled',true);
               // $('#saveLoader').show();
        },
        success: function(result){
            //alert(result);
             //console.log(result);
            var obj = JSON.parse(result);
           
             if(obj['result'] == 'success'){
                 var len = (obj['data'].length);
                    for (var key in obj['data']) {
                        
                        var len1 = (obj['data'][key].length);
                       
                            for(var i=0;i<len1; i++){
                                $('#'+key+(i)).val(obj['data'][key][i]);
                            }
                       
                    }
                  if(obj['status'] == "0"){
                        $('#show').hide();
                        $('#hide').show();
                    }
                    if(obj['status'] == "1"){
                        $('#show').show();
                        $('#hide').hide();
                    }  
             }
            if(obj['result'] == 'failed'){
                //alert(obj['status']);
                    if(obj['status'] == "0"){
                        $('#show').hide();
                        $('#hide').show();
                    }
                    if(obj['status'] == "1"){
                        $('#show').show();
                        $('#hide').hide();
                    }
             }
             


        },error: function(data){
                //alert("error");
                console.log(data);
        },complete: function(){
                //alert('complete');
               // $('#saveBtn').html('Add Candidate');
               // $('#saveBtn').prop('disabled',false);
               // $('#saveLoader').hide();
        }  
    });
    return false;
}



</script>


@endsection