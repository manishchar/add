 {!! Form::open(array('url' => 'resume','enctype'=>'multipart/form-data','id'=>'basicInformation')) !!}
	 <input type="hidden"  name="_token" id="csrf-token" value="{{ 
	 Session::token() }}" />

	 <select name="HiringManagerID">
	 	<option>Select HM</option>
	 	<option value="1">One</option>
	 	<option value="2">Two</option>
	 	<option value="3">Three</option>
	 </select>
  <input type="text" name="JobTitle" placeholder="Time">
  <input type="submit" value="Save">
{!! Form::close() !!}