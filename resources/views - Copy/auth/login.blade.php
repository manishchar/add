<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<title>Beem Stack</title>
<meta content="Admin Dashboard" name="description" />
<meta content="Themesdesign" name="author" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!-- App Icons -->
<link rel="shortcut icon" href="assets/images/favicon.ico">
<!-- App css -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
<!-- Begin page -->
<div class="accountbg"></div>
<div class="wrapper-page">
  <div class="card">
    <div class="card-body">
      <h3 class="text-center mt-0 m-b-15"> <a href="index.html" class="logo logo-admin"><img src="{!!URL('/')!!}/assets/images/logo2.png" height="100" alt="logo"></a> </h3>
      <h4 class="text-muted text-center font-18"><b>Sign In</b></h4>
      <div class="p-3">
        <form class="form-horizontal m-t-20" method="POST" action="{{ route('login') }}">
          {{ csrf_field() }}
          <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
            <div class="col-12">
              <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus>
               @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
               @endif
            </div>
          </div>
          <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
            <div class="col-12">
              <input  type="password" class="form-control" name="password" required placeholder="Password">

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
          </div>
          <div class="form-group row">
            <div class="col-12">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="custom-control-label" for="customCheck1">Remember me</label>
              </div>
            </div>
          </div>
          <div class="form-group text-center row m-t-20">
            <div class="col-12">
              <button type="submit" class="btn btn-info btn-block waves-effect waves-light" >Log In</button>
            </div>
          </div>
          <div class="form-group m-t-10 mb-0 row">
            <div class="col-sm-7 m-t-20"> <a href="" class="text-muted">
              <a href="{{ URL('password/reset') }}">
                <i class="mdi mdi-lock    "></i> Forgot your password?</a> 
              </a>
              
            </div>
            <!--<div class="col-sm-5 m-t-20"> <a href="pages-register.html" class="text-muted"><i class="mdi mdi-account-circle"></i> Create an account</a> </div>-->
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- jQuery  -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
<script src="{{ asset('assets/js/waves.js') }}"></script>
<script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>
<script src="{{ asset('assets/js/jquery.scrollTo.min.js') }}"></script>
<!-- App js -->
<script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
