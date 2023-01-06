<!DOCTYPE html>
<html lang="en">

<head>
@include('layout.user.partials.head')
</head>

<body style="position: relative">
    <section id="container">
        <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
        <!--header start-->
        <header class="header black-bg">
            <div class="sidebar-toggle-box">
                <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
            </div>
            <!--logo start-->
            <a href="#" class="logo"><b>MAKE<span>IT</span></b></a>
            <!--logo end-->
            <!--Notifications ici-->

            <div class="top-menu">

                <ul class="nav pull-right top-menu">
                    <div class="nav notify-row" id="top_menu" style="margin-right: 10em">
                        <!--  notification start -->
                        <ul class="nav top-menu">
                            <li id="header_notification_bar" class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <i class="fa fa-user"> {{ auth()->user()->identifiant }} </i> <i class="fa fa-chevron-down"></i>
                                </a>
                                <ul class="dropdown-menu extended notification">
                                    <div class="notify-arrow notify-arrow-white"></div>
                                    {{-- <li>
                                        <p class="yellow">You have 7 new notifications</p>
                                    </li> --}}
                                    <li>
                                        <a href="{{ route('profile') }}">
                                            <span class="label label-success"><i class="fa fa-user"></i></span>
                                            Mon Profil
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('logout') }}">
                                            <span class="label label-danger"><i class="fa fa-power-off"></i></span>
                                            Déconnexion
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <!-- notification dropdown end -->
                        </ul>
                        <!--  notification end -->
                    </div>
                    {{-- <li><a class="logout" href="{{ url('connexion') }}">Se déconnecter</a></li> --}}
                </ul>
            </div>
        </header>
        <!--header end-->
        <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
        <!--sidebar start-->
       @include('layout.user.partials.sidebar')
        <!--sidebar end-->
        <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                @yield('content')
                <!-- /row -->
            </section>
        </section>
        <!--main content end-->
        <!--footer start-->
        @include('layout.user.partials.footer')
        <!--footer end-->
    </section>

@include('layout.user.partials.scriptjs')
</body>

</html>
