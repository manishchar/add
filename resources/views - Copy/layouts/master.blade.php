<?php
  $web_setting = App\Websitesetting::first();
  $logout_time = 1000*60*($web_setting->locktimeout); 
?>
<!DOCTYPE html>
<html>
@include('includes.head')
<head>
        
    </head>


    <body>

        <!-- Loader -->
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>

        <!-- Navigation Bar-->
        @include('includes.header')
        <!-- End Navigation Bar-->


        <div class="wrapper">
          @yield('content')
        </div>
        <!-- end wrapper -->


        <!-- Footer -->
        @include('includes.footer')
        @yield('extrajs')
    </body>

</html>