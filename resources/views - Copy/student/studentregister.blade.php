@extends('layouts.frontmaster')

@section('content')

 <section>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Register</h4>
                            <form method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="md-face"></i>
                                        </div>
                                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus placeholder="Your name">
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="md-email"></i>
                                        </div>
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="Email address">
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                            <div class="input-group-addon">
                                                <i class="md-lock"></i>
                                            </div>
                                            <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="md-lock"></i>
                                            </div>
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm password">
                                        </div>
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                                </div>
                            </form>
                            <ul class="list-inline">
                                <li><a href="{!!URL('/')!!}/student-login">Login</a></li>
                                <li class="pull-right"><a href="javascript:;">I forgot my password!</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection