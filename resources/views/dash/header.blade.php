<body class="fix-header fix-sidebar card-no-border">
  <!-- ============================================================== -->
  <!-- Preloader - style you can find in spinners.css -->
  <!-- ============================================================== -->

  <!-- ============================================================== -->
  <!-- Main wrapper - style you can find in pages.scss -->
  <!-- ============================================================== -->
  <div id="main-wrapper">
      <header class="topbar">
          <nav class="navbar top-navbar navbar-toggleable-sm navbar-light">
              <!-- ============================================================== -->
              <!-- Logo -->
              <!-- ============================================================== -->
              <div class="navbar-header " style="background-color: #fff !important" >
                  <a class="navbar-brand" href="{{route('dashboard')}}">
                      <b>
                      
                          <h2 class="light-logo" >
                            <img src="{{ asset('img/logo.png') }}" width="90px" height="70px" alt="homepage" class="light-logo d-none d-sm-inline" />

                          </h2>
                      </b>
                      <!--End Logo icon -->

                      <span>
                        
                        <span> <!-- Logo text --> <!-- Light Logo text -->
                            <img src="/img/admin-logo-text.png" class="light-logo" alt="homepage" />
                        </span>
                      </span>
                  </a>
              </div>
              <!-- ============================================================== -->
              <!-- End Logo -->
              <!-- ============================================================== -->
              <div class="navbar-collapse">
                  <!-- ============================================================== -->
                  <!-- toggle and nav items -->
                  <!-- ============================================================== -->
                  <ul class="navbar-nav mr-auto mt-md-0">
                      <!-- This is  -->
                      <li class="nav-item">
                          <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)">
                              <i class="mdi mdi-menu"></i>
                          </a>
                      </li>

                      <!-- ============================================================== -->
                    
                      
                  </ul>
                  <!-- ============================================================== -->
                  <!-- User profile and search -->
                  <!-- ============================================================== -->
                  <ul class="navbar-nav my-lg-0">
                      <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                              <img src="{{asset('img/profile_default_photo.png')}}" alt="user" class="profile-pic" />
                          </a>
                          <div class="dropdown-menu dropdown-menu-right scale-up show">
                              <ul class="dropdown-user">
                                <li>
                                    <h2 style="margin-left: 15px">hi {{ auth()->user()->name }}</h2>
                                </li>
                                  <li>
                                      <a href="{{route('profile.edit')}}"><i class="ti-settings"></i> Settings</a>
                                  </li>
                                 
                                  <li role="separator" class="divider"></li>
                                  <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
            
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                      {{-- <a href=""><i class="fa fa-power-off"></i> Logout</a> --}}
                                  </li>
                              </ul>
                          </div>
                      </li>
                  </ul>
              </div>
          </nav>
      </header>
  </div>
</body>
