@extends('layouts.frontmaster')

@section('content')

  
    <section>

        <div class="container">
            <header class="section-header">
                <h3>Mail Notification</h3>
               
            </header>
            <div class="card">
          
                <div class="card-body">
				   @if(Session::has('message'))
				            <p> {!! Session::get('message') !!} </p>
				    @endif
					 <br/><br/><br/>
					@if(Session::has('status'))
						@if(Session::get('status') == '1')
					        <p>Login Hare</p>
					        <a href="{{ URL('admin/login') }}">Login</a>
					    @endif
					@endif
               

              
                </div>
            </div>
           
        </div>
    </section>
   
   
@endsection 