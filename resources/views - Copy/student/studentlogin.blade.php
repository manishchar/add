@extends('layouts.frontmaster')

@section('content')

<section>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Login</h4>
                            <form method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}

                                <div class="input-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <span class="input-group-addon">
                                        <i class="md-email"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control" placeholder="Email address" value="{{ old('email') }}" required autofocus>
                                    @if ($errors->has('email'))
					                <span class="help-block">
					                    <strong>{{ $errors->first('email') }}</strong>
					                </span>
					               @endif
                                </div>
                                <div class="input-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <span class="input-group-addon">
                                            <i class="md-lock"></i>
                                        </span>
                                    <input type="password" class="form-control" name="password" required placeholder="Password">
                                    @if ($errors->has('password'))
					                    <span class="help-block">
					                        <strong>{{ $errors->first('password') }}</strong>
					                    </span>
					                @endif
                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" checked>
                                            <span class="checkbox-text">Remember me</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </div>
                            </form>
                            <ul class="list-inline">
                                <li><a href="{!!URL('/')!!}/student-register">Register</a></li>
                                <li class="pull-right"><a href="javascript:;">I forgot my password</a></li>
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
@endsection