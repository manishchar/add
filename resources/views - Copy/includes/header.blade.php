<header id="topnav">
    
    @if(Auth::check())
  @php  $roles = Auth::user()->roles()->pluck('name')->implode(' ');
   @endphp
  @endif
  
  <div class="topbar-main">
    <div class="container-fluid">
      <!-- Logo container-->
      <div class="logo">
        
        <a href="{!!URL('/')!!}/admin/dashboard" class="logo"> <img src="{!!URL('/')!!}/assets/images/logo-sm.png" alt="" height="22" class="logo-small"> <img src="{!!URL('/')!!}/assets/images/logo21.png" alt="" height="80" class="logo-large"> </a> </div>
      <!-- End Logo container-->
      <div class="menu-extras topbar-custom">
        <!-- Search input -->
        <div class="search-wrap" id="search-wrap">
          <div class="search-bar">
            <input class="search-input" type="search" placeholder="Search" />
            <a href="#" class="close-search toggle-search" data-target="#search-wrap"> <i class="mdi mdi-close-circle"></i> </a> </div>
        </div>
        <ul class="list-inline float-right mb-0">
          <!-- Search -->
          
          
          <!-- notification-->
         
          <!-- User-->
          @if(Auth::check())
          <li class="list-inline-item dropdown notification-list">   {{Auth::user()->name}} 
            <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false"
                           style="background: #bdbbb7;border-radius: 25px;padding: 0px 20px;" > 
                           @php
                           $arr = explode(' ',Auth::user()->name);
                            if($arr[0] ){
                              echo strtoupper(substr($arr[0], 0, 1));
                            }
                            if(isset($arr[1])){
                              echo ' '.strtoupper(substr($arr[1], 0, 1));
                            }
                           @endphp
                           <!-- <img src="{!!URL('/')!!}/assets/images/users/avatar-1.jpg" alt="user" class="rounded-circle">  -->
                         </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown "> 
                <a class="dropdown-item" href="{!!URL('/')!!}/admin/profile"><i class="dripicons-user text-muted"></i> Profile</a>  
                <a class="dropdown-item" href="{!!URL('/')!!}/admin/websitesetting"><span class="badge badge-success pull-right m-t-5"></span><i class="dripicons-gear text-muted"></i> Settings</a> 
                <a class="dropdown-item" href="<?= URL::to('admin/screenlock'); ?>/<?= time();?>/<?= $email = Auth::user()->id; ?>/<?= MD5(str_random(10)) ?>"><i class="dripicons-lock text-muted"></i> Lock screen</a>
              
                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('client.index') }}"><i class="dripicons-gear text-muted"></i> Client</a>

                <a class="dropdown-item" href="{{ route('location.index') }}"><i class="dripicons-gear text-muted"></i> Location</a>

                <a class="dropdown-item" href="{{ route('advertise.index') }}"><i class="dripicons-gear text-muted"></i>Advertisement</a>

                <a class="dropdown-item" href="{{ route('skill.index') }}"><i class="dripicons-gear text-muted"></i> Skill</a>
                 <div class="dropdown-divider"></div>
              <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="dripicons-exit text-muted"></i>Logout</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
            </div>
          </li>@endif
          <li class="menu-item list-inline-item">
            <!-- Mobile menu toggle-->
            <a class="navbar-toggle nav-link">
            <div class="lines"> <span></span> <span></span> <span></span> </div>
            </a>
            <!-- End mobile menu toggle-->
          </li>
        </ul>
      </div>
      <!-- end menu-extras -->
      <div class="clearfix"></div>
    </div>
    <!-- end container -->
  </div>
  <!-- end topbar-main -->
  <!-- MENU Start -->
  
  <div class="navbar-custom">
    <div class="container-fluid">
      <div id="navigation">
        <!-- Navigation Menu-->
        <ul class="navigation-menu">
          <li class="has-submenu"> <a href="{{ URL('admin/dashboard') }}"><i class="ti-home"></i>Dashboard</a> </li>

          @if($roles=='Super Admin')
          
          
          <li class="has-submenu"> <a href="{{ route('roles.index') }}"><i class="ti-home"></i>Role</a> </li>
           <li class="has-submenu"> <a href="{{ route('users.index') }}"><i class="ti-home"></i>Users</a> </li>

          @elseif($roles=='Admin')
         <li class="has-submenu"> <a href="{{ route('users.index') }}"><i class="ti-home"></i>Users</a> </li>
          @endif


         
          <li class="has-submenu"> <a href="{{ route('client.index') }}"><i class="ti-home"></i>Client</a> </li>
          <li class="has-submenu"> <a href="{{ route('location.index') }}"><i class="ti-home"></i>Location</a> </li>
          <li class="has-submenu"> <a href="{{ route('advertise.index') }}"><i class="ti-home"></i>Advertisement</a> </li>
          <li class="has-submenu"> <a href="#"><i class="ti-home"></i>Schedule</a> </li>
          <li class="has-submenu"> <a href="#"><i class="ti-home"></i>Report</a> </li>

         
          
          
         
        </ul>
        <!-- End navigation menu -->
      </div>
      <!-- end #navigation -->
    </div>
    <!-- end container -->
  </div>
  <!-- end navbar-custom -->
</header>
