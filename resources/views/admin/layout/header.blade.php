<div class="mobile-author-actions"></div>
<header class="header-top">
    <nav class="navbar navbar-light">
        <div class="navbar-left">
            <a href="#" class="sidebar-toggle">
                <img class="svg" src="{{ asset('backend') }}/img/svg/bars.svg" alt="img"></a>
            <a class="navbar-brand" href="#"><img class="svg dark" src="{{ asset('backend') }}/img/svg/logo_dark.svg" alt="svg"><img class="light" src="{{ asset('backend') }}/img/logo_white.png" alt="img"></a>
            <div class="top-menu">

                <div class="strikingDash-top-menu position-relative">
                    <ul>
                        <li class="has-subMenu">
                            <a href="#" class="active">Dashboard</a>
                        </li>


                        <li class="has-subMenu">
                            <a href="#" class="">Crud</a>
                            <ul class="subMenu">
                                <li class="has-subMenu-left">
                                    <a href="#" class="">
                                        <span data-feather="shopping-cart" class="nav-icon"></span>
                                        <span class="menu-text">Firestore Crud</span>
                                    </a>
                                    <ul class="subMenu">
                                        <li>
                                            <a class="" href="firestore.html">View All</a>
                                        </li>
                                        <li>
                                            <a class="" href="firestore-add.html">Add
                                                New</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <!-- ends: navbar-left -->

        <div class="navbar-right">
            <ul class="navbar-right__menu">
                <li class="nav-author">
                    <div class="dropdown-custom">
                        <a href="javascript:;" class="nav-item-toggle"><img src="{{ asset('backend') }}/img/author-nav.jpg" alt="" class="rounded-circle"></a>
                        <div class="dropdown-wrapper">
                            <div class="nav-author__info">
                                <div class="author-img">
                                    <img src="{{ asset('backend') }}/img/author-nav.jpg" alt="" class="rounded-circle">
                                </div>
                                <div>
                                    <h6>Abdullah Bin Talha</h6>
                                    <span>UI Designer</span>
                                </div>
                            </div>
                            <div class="nav-author__options">
                                <ul>
                                    <li>
                                        <a href="#">
                                            <span data-feather="user"></span> Profile</a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span data-feather="settings"></span> Settings</a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span data-feather="key"></span> Billing</a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span data-feather="users"></span> Activity</a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span data-feather="bell"></span> Help</a>
                                    </li>
                                </ul>
                                <a href="#" class="nav-author__signout">
                                    <span data-feather="log-out"></span> Sign Out</a>
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
