    <aside class="left-sidebar">
    <div class="scroll-sidebar">
        <!-- Sidebar scroll-->

        <nav class="sidebar-nav">
            <!-- Sidebar navigation-->

            <ul id="sidebarnav">
                <li class="nav-small-cap">General</li>

                <li>
                    <a class="waves-effect waves-dark active" href="{{route('dashboard')}}" aria-expanded="false">
                        <i class="ti-dashboard"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)
                    <li>
                        <a class="waves-effect waves-dark" href="{{ route('admin.gifts') }}" aria-expanded="false">
                            <i class="ti-gift"></i>
                            <span class="hide-menu">Gifts</span>
                        </a>
                    </li>
                @endif

                @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)
                    <li>
                        <a class="waves-effect waves-dark" href="{{ route('admin.stickers') }}" aria-expanded="false">
                            <i class="ti-face-smile"></i>
                            <span class="hide-menu">Stickers</span>
                        </a>
                    </li>
                @endif


                @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)
                <li>
                    <a class="waves-effect waves-dark" href="{{route('users.advert')}}" aria-expanded="false">
                        <i class="ti-layout-list-post"></i>
                        <span class="hide-menu">Adverts</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)
                <li>
                    <a class="waves-effect waves-dark" href="{{route('admins.push')}}" aria-expanded="false">
                        <i class="ti-bell"></i>
                        <span class="hide-menu">Push Notifications</span>
                    </a>
                </li>
                @endif
                
         
                @if(Auth::user()->user_role != 3 )
                <li class="nav-devider"></li>



                <li class="nav-small-cap">User Management</li>

                <li>
                    <a class="waves-effect waves-dark" href="{{route('users.index')}}" aria-expanded="false">
                        <i class="ti-image"></i>
                        <span class="hide-menu">All Users</span>
                    </a>
                </li>
                @if(Auth::user()->user_role != 4 )
                <li>
                    <a class="waves-effect waves-dark" href="{{route('users.referal')}}" aria-expanded="false">
                        <i class="ti-image"></i>
                        <span class="hide-menu">Referals</span>
                    </a>
                </li>
                @endif
                @endif
                {{-- @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)
                <li>
                    <a class="waves-effect waves-dark" href="{{route('users.verify')}}" aria-expanded="false">
                        <i class="ti-comment-alt"></i>
                        <span class="hide-menu">Verifield Users</span>
                    </a>
                </li>
                @endif --}}
                @if(Auth::user()->user_role != 3 )
                <li>
                    <a class="waves-effect waves-dark" href="{{route('admins.payment')}}" aria-expanded="false">
                        <i class="ti-comments"></i>
                        <span class="hide-menu">Payments</span>
                    </a>
                </li>
                @endif
                <li class="nav-devider"></li>
                @if( Auth::user()->user_role != 4)
                <li class="nav-small-cap">Reports</li>
                @endif
                @if( Auth::user()->user_role != 4)
               
                <li>
                    <a class="waves-effect waves-dark" href="{{route('admins.help')}}" aria-expanded="false">
                        <i class="ti-help-alt"></i>
                        <span class="hide-menu">Help</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="{{route('report.index')}}" aria-expanded="false">
                        <i class="ti-face-sad"></i>
                        <span class="hide-menu">Profile Reports</span>
                    </a>
                </li>
                @if( Auth::user()->user_role != 1 && Auth::user()->user_role != 2 && Auth::user()->user_role != 4)
                <li>
                    <a class="waves-effect waves-dark" href="{{route('profile.edit')}}" aria-expanded="false">
                        <i class="ti-settings"></i>
                        <span class="hide-menu">Settings</span>
                    </a>
                </li>
                @endif
                @endif
            
                @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)
                <li class="nav-devider"></li>

                <li class="nav-small-cap">Admin Management</li>
             
                <li>
                    <a class="waves-effect waves-dark" href="{{route('roles')}}" aria-expanded="false">
                        <i class="ti-mobile"></i>
                        <span class="hide-menu">Admin Roles</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)
                <li class="nav-devider"></li>

                <li class="nav-small-cap">Settings</li>

                <li>
                    <a class="waves-effect waves-dark" href="{{route('profile.edit')}}" aria-expanded="false">
                        <i class="ti-settings"></i>
                        <span class="hide-menu">Settings</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->user_role != 3 && Auth::user()->user_role != 4)
                <li>
                    <a class="waves-effect waves-dark" href="{{route('admininistrators')}}" aria-expanded="false">
                        <i class="ti-crown"></i>
                        <span class="hide-menu">Administrators</span>
                    </a>
                </li>
                @endif

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
    </aside>
