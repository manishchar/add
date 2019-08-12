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
        <h4 class="page-title"></h4>
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

          @include('includes.reportHeader')

        <form>
          <div class="row">
            
              <div class="col-md-12">
                <div class="row">
                   <div class="col-md-4">
                    <div class="form-group"> 
                      <label>Location: <span class="text text-danger">*</span></label>
                      <div> 
                        <select class="selectpicker form-control" name="location" id="location">
                          <option value="0" selected="">All</option>
                          @foreach($locations as $location)
                          <option value="{{ $location->id }}" @if(isset($_REQUEST['location'])) @if($_REQUEST['location'] ==$location->id ) {{ 'selected' }}@endif  @endif>{{ $location->location }}</option>
                          @endforeach
                        </select>
                       </div>
                    </div>
                  </div>

                   <div class="col-md-4">
                    <div class="form-group"> 
                      <label>Advertise: <span class="text text-danger">*</span></label>
                      <div> 
                        @php
                          $clients=array();
                        @endphp
                        <select class="selectpicker form-control" name="advertise" id="advertise">
                          <option value="0" selected="">All</option>
                          @foreach($advertises as $advertise)
                             @if(isset($_REQUEST['advertise']))
                               @if($_REQUEST['advertise'] == $advertise->id )
                               @php $clients[]=$advertise->client;  @endphp
                               @endif
                             @else
                              @php $clients[]=$advertise->client;@endphp
                             @endif
                          <option value="{{ $advertise->id }}" @if(isset($_REQUEST['advertise'])) @if($_REQUEST['advertise'] ==$advertise->id ) {{ 'selected' }}@endif  @endif>{{ $advertise->advertise_name }}</option>
                          @endforeach
                        </select>
                       </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group"> 
                      <label>Client: <span class="text text-danger">*</span></label>
                      <div> 
                        <select class="selectpicker form-control" name="client" id="client">
                          <option value="0" selected="">ALL</option>
                          @foreach($clients as $client)
                          <option value="{{ $client->id }}" @if(isset($_REQUEST['client'])) @if($_REQUEST['client'] ==$client->id ) {{ 'selected' }}@endif  @endif >{{ $client->fname.' '.$client->lname }}</option>
                          @endforeach
                        </select>
                       </div>
                    </div>
                  </div>
                 
                 

                  {{-- <div class="col-md-3">
                    <div class="form-group"> 
                      <div> 
                        <label>Start Date : <span class="text text-danger">*</span></label>
                         <input type="text" autocomplete="off" class="form-control datepicker" id="from" name="startDate" value="@if(isset($_REQUEST['startDate'])) {{ $_REQUEST['startDate'] }}  @endif"> 
                       </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group"> 
                      <div> 
                        <label>End Date : <span class="text text-danger">*</span></label>
                         <input type="text" autocomplete="off" class="form-control datepicker" id="to" name="endDate" value="@if(isset($_REQUEST['startDate'])){{ $_REQUEST['endDate'] }} @endif"> 
                      </div>
                    </div>
                  </div> --}}
                  
                  <div class="col-md-12">
                      <div class="form-group"> 
                        <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
                        <button type="reset" class="btn btn-secondary waves-effect m-l-5"> Cancel </button>   
                      </div>
                  </div>
                </div>
              </div>
             
          </div>
        </form> 
        
          </div>
        


          <!-- end page title end breadcrumb -->
          <div class="row">

           <div class="col-lg-12">
              <div class="card m-b-30">
                <div class="card-body">
                  <table id="report" class="table">
                    <thead>
                      <tr>
                        <th width="5%">#</th>
                        <th >Location</th>
                        <th >Advertise Name</th>
                        <th >Client Name</th>
                        <th >Start Date</th>
                        <th >End Date</th>
                        <th >Status</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                    
                    @php $count = 1; @endphp
                    @if(isset($schedules) && !empty($schedules))
                    @foreach ($schedules as $key=>$value)
                    <tr>
                      <td>{{ $count }}</td>
                      <td>{{ $value->client->fname.' '.$value->client->lname }}</td>
                      <td>{{ $value->advertise->advertise_name }}</td>
                      <td>{{ $value->advertise->location->location }}</td>
                      <td>{{ $value->fromDate }}</td>
                      <td>{{ $value->toDate }}</td>
                      <td>{{ $count }}</td>
                     
                     
                      
                      
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script type="text/javascript">
  


$('#location').change(function(event) {
  var locationId = $(this).val();
  var _token = "{{ csrf_token() }}";
  $.ajax({
    type: "POST", 
    url: "{{ URL('/admin/changeLocation') }}", 
    data: {_token:_token,locationId:locationId}, 
    success: function(result){
       
      var locationHtm = "";
      var advertiseHtm = "";
      var clientHtm = "";
      var obj = JSON.parse(result);
        if(obj.advertise.length >0){
            //locationHtm += "<option value='0' selected disabled>Select Location</option>";
            clientHtm += "<option value='0' selected disabled>Select Client</option>";
            advertiseHtm += "<option value='0' selected disabled>Select Advertise</option>";
            for(var i=0;i<obj.advertise.length;i++){

              var obj1 = obj.advertise[i];
             //console.log(obj1.location);
             // locationHtm += "<option value='"+obj1.id+"'>"+obj1.location.location+"</option>";
              clientHtm += "<option value='"+obj1.id+"'>"+obj1.client.fname+"</option>";
              advertiseHtm += "<option value='"+obj1.id+"'>"+obj1.advertise_name+"</option>";
            }
        }else{
             //locationHtm += "<option value='0' selected disabled>No Location</option>";
             clientHtm += "<option value='0' selected disabled>No Client</option>";
             advertiseHtm += "<option value='0' selected disabled>No Advertise</option>";
        }
      //advertise
      $("#advertise").html(advertiseHtm);
      $("#client").html(clientHtm);
       
    }
  }); // ajax closing tag

});

$('#client').change(function(event) {
  // var id = $(this).val();
  // var _token = "{{ csrf_token() }}";
  // $.ajax({
  //   type: "POST", 
  //   url: "{{ URL('/admin/getAdvertise') }}", 
  //   data: {_token:_token,id:id}, 
  //   success: function(result){
       
  //     var locationHtm = "";
  //     var advertiseHtm = "";
  //     var clientHtm = "";
  //     var obj = JSON.parse(result);
  //       if(obj.advertise.length >0){
  //           locationHtm += "<option value='0' selected disabled>Select Location</option>";
  //           clientHtm += "<option value='0' selected disabled>Select Client</option>";
  //           advertiseHtm += "<option value='0' selected disabled>Select Advertise</option>";
  //           for(var i=0;i<obj.advertise.length;i++){

  //             var obj1 = obj.advertise[i];
  //            //console.log(obj1.location);
  //             locationHtm += "<option value='"+obj1.id+"'>"+obj1.location.location+"</option>";
  //             clientHtm += "<option value='"+obj1.id+"'>"+obj1.client.fname+"</option>";
  //             advertiseHtm += "<option value='"+obj1.id+"'>"+obj1.advertise_name+"</option>";
  //           }
  //       }else{
  //            locationHtm += "<option value='0' selected disabled>No Location</option>";
  //            clientHtm += "<option value='0' selected disabled>No Client</option>";
  //            advertiseHtm += "<option value='0' selected disabled>No Advertise</option>";
  //       }
  //     //advertise
  //     $("#advertise").html(advertiseHtm);
  //     $("#location").html(locationHtm);
       
  //   }
  // }); // ajax closing tag

});

$('#advertise').change(function(event) {
  var locationId = $('#location').val();
  var advertiseId = $(this).val();
  var _token = "{{ csrf_token() }}";
  $.ajax({
    type: "POST", 
    url: "{{ URL('/admin/getClientByLocation') }}", 
    data: {_token:_token,advertiseId:advertiseId,locationId:locationId}, 
    success: function(result){
       
      var locationHtm = "";
      var advertiseHtm = "";
      var clientHtm = "";
      var obj = JSON.parse(result);
        if(obj.advertise.length >0){
            //locationHtm += "<option value='0' selected disabled>Select Location</option>";
            clientHtm += "<option value='0' selected disabled>Select Client</option>";
            for(var i=0;i<obj.advertise.length;i++){
              var obj1 = obj.advertise[i];
             //console.log(obj1.location);
              //locationHtm += "<option value='"+obj1.id+"'>"+obj1.location.location+"</option>";
              clientHtm += "<option value='"+obj1.id+"'>"+obj1.client.fname+"</option>";
            }
        }else{
             //locationHtm += "<option value='0' selected disabled>No Location</option>";
             clientHtm += "<option value='0' selected disabled>No Client</option>";
        }
      $("#client").html(clientHtm);
       
    }
  }); // ajax closing tag

});


   $( function() {
      var dateFormat = "mm-dd-yy",

      from = $( "#from" )
        .datepicker({
          defaultDate: "+1w",
          maxDate: new Date(),
          changeMonth: true,
          dateFormat : "mm-dd-yy"
          // numberOfMonths: 3
        }).on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        maxDate: new Date(),
        dateFormat : "mm-dd-yy"
        // numberOfMonths: 3
      }).on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
      function getDate( element ) {
        var date;
        try {
          date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
          date = null;
        }
   
        return date;
      }


      $('.selectpicker').select2({
      width: '100%',
      // placeholder: "Select Transaction Type",
    });
  });



</script>


<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<style type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"></style>
<style type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"></style>

<script type="text/javascript">
  $(document).ready(function() {
    $('#report').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

</script>
@endsection











