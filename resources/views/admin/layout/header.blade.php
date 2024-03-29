<div class="mobile-author-actions"></div>
<header class="header-top">
    <nav class="navbar navbar-light">
        <div class="navbar-left">
            <a href="#" class="sidebar-toggle">
                <img class="svg" src="{{ asset('backend') }}/img/svg/bars.svg" alt="img"></a>
{{--    removed the dashboard logo    --}}
{{--            <a class="navbar-brand" href="#"><img class="svg dark" src="{{ asset('backend') }}/img/svg/logo_dark.svg" alt="svg"><img class="light" src="{{ asset('backend') }}/img/logo_white.png" alt="img"></a>--}}
            <a class="navbar-brand">Attendance Management System</a>
            <div class="top-menu">

                <div class="strikingDash-top-menu position-relative">
                    <ul>
                        <li class="has-subMenu">
                            <a href="#" class="active">Dashboard</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <!-- ends: navbar-left -->

        <div class="navbar-right">
            <ul class="navbar-right__menu">
                <h6>{{ auth()->user()->name }}</h6>
                <li class="nav-author">
                    <div class="dropdown-custom">
                        <a href="javascript:;" class="nav-item-toggle"><img src="{{ asset('backend') }}/img/author-nav.jpg" alt="" class="rounded-circle"></a>
                        <div class="dropdown-wrapper">
                            <div class="nav-author__info">
                                <div class="author-img">
                                    <img src="{{ asset('backend') }}/img/author-nav.jpg" alt="" class="rounded-circle">
                                </div>
                                <div>
                                    <h6>{{ auth()->user()->name }}</h6>
                                </div>
                            </div>
                            <div class="nav-author__options">
                                <ul>
                                    <li>
                                        <a href="{{ route('admin.change.password') }}">
                                            <span data-feather="user"></span> Change Password</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.profile') }}">
                                            <span data-feather="user"></span>Update Profile</a>
                                    </li>
                                </ul>
                                <a class="nav-author__signout" href="{{ route('admin.logout') }}"
                                      onclick="event.preventDefault();
                                      document.getElementById('admin-logout-form').submit();">Sign Out</a>
                                <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                        <!-- ends: .dropdown-wrapper -->
                    </div>
                </li>
                <!-- ends: .nav-author -->
            </ul>
        </div>
        <!-- ends: .navbar-right -->
    </nav>
</header>
