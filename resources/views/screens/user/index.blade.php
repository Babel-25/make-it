<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <title>MAKE IT - Ma Page</title>

    <!-- Favicons -->
    <link href="{{ asset('/UserTemp/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('/UserTemp/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/UserTemp/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('/UserTemp/lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/UserTemp/css/zabuto_calendar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/UserTemp/lib/gritter/css/jquery.gritter.css') }}" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('/UserTemp/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/UserTemp/css/style-responsive.css') }}" rel="stylesheet">
    <script src="{{ asset('/UserTemp/lib/chart-master/Chart.js') }}"></script>

</head>

<body>
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
                            <!-- settings start -->

                            <!-- settings end -->
                            <!-- inbox dropdown start-->

                            <!-- inbox dropdown end -->
                            <!-- notification dropdown start-->
                            <li id="header_notification_bar" class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <i class="fa fa-user"> Utilisateur </i> <i class="fa fa-chevron-down"></i>
                                </a>
                                <ul class="dropdown-menu extended notification">
                                    <div class="notify-arrow notify-arrow-white"></div>
                                    {{-- <li>
                                        <p class="yellow">You have 7 new notifications</p>
                                    </li> --}}
                                    <li>
                                        <a href="{{ url('profil') }}">
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
        <aside>
            <div id="sidebar" class="nav-collapse ">
                <!-- sidebar menu start-->
                <ul class="sidebar-menu" id="nav-accordion">
                    {{-- <p class="centered"><a href="{{ url('profil') }}"><img src="img/ui-sam.jpg" class="img-circle"
                                width="80">
                            <h5 class="centered">Utilisateur</h5>
                        </a></p> --}}
                    <li class="mt">
                        <a class="active" href="{{ route('dashboard') }}">
                            <i class="fa fa-dashboard"></i>
                            <span>Accueil</span>
                        </a>
                    </li>


                    {{-- <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-book"></i>
                            <span></span>
                        </a>
                        <ul class="sub">
                            <li><a href="500.html">500 Error</a></li>
                        </ul>
                    </li> --}}



                </ul>
                <!-- sidebar menu end-->
            </div>
        </aside>
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
        <footer class="site-footer">
            <div class="text-center">
                <p>
                    &copy; Copyrights <strong>Dashio</strong>. All Rights Reserved
                </p>
                <div class="credits">
                    <!--
            You are NOT allowed to delete the credit link to TemplateMag with free version.
            You can delete the credit link only if you bought the pro version.
            Buy the pro version with working PHP/AJAX contact form: https://templatemag.com/dashio-bootstrap-admin-template/
            Licensing information: https://templatemag.com/license/
          -->
                    Created with Dashio template by <a href="#">TemplateMag</a>
                </div>

            </div>
        </footer>
        <!--footer end-->
    </section>
    <!-- js placed at the end of the document so the pages load faster -->
    <script src="{{ asset('/UserTemp/lib/jquery/jquery.min.js') }}"></script>

    <script src="{{ asset('/UserTemp/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    <script class="include" type="text/javascript" src="{{ asset('/UserTemp/lib/jquery.dcjqaccordion.2.7.j') }}s"></script>
    <script src="{{ asset('/UserTemp/lib/jquery.scrollTo.min.js') }}"></script>
    <script src="{{ asset('/UserTemp/lib/jquery.nicescroll.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/UserTemp/lib/jquery.sparkline.js') }}"></script>
    <!--common script for all pages-->
    <script src="{{ asset('/UserTemp/lib/common-scripts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/UserTemp/lib/gritter/js/jquery.gritter.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/UserTemp/lib/gritter-conf.js') }}"></script>
    <!--script for this page-->
    <script src="{{ asset('/UserTemp/lib/sparkline-chart.js') }}"></script>
    <script src="{{ asset('/UserTemp/lib/zabuto_calendar.js') }}"></script>
    {{-- <script type="text/javascript">
    $(document).ready(function() {
      var unique_id = $.gritter.add({
        // (string | mandatory) the heading of the notification
        title: 'Welcome to Dashio!',
        // (string | mandatory) the text inside the notification
        text: 'Hover me to enable the Close Button. You can hide the left sidebar clicking on the button next to the logo.',
        // (string | optional) the image to display on the left
        image: 'img/ui-sam.jpg',
        // (bool | optional) if you want it to fade out on its own or just sit there
        sticky: false,
        // (int | optional) the time you want it to be alive for before fading out
        time: 8000,
        // (string | optional) the class name you want to apply to that specific message
        class_name: 'my-sticky-class'
      });

      return false;
    });
  </script> --}}
    <script type="application/javascript">
    $(document).ready(function() {
      $("#date-popover").popover({
        html: true,
        trigger: "manual"
      });
      $("#date-popover").hide();
      $("#date-popover").click(function(e) {
        $(this).hide();
      });

      $("#my-calendar").zabuto_calendar({
        action: function() {
          return myDateFunction(this.id, false);
        },
        action_nav: function() {
          return myNavFunction(this.id);
        },
        ajax: {
          url: "show_data.php?action=1",
          modal: true
        },
        legend: [{
            type: "text",
            label: "Special event",
            badge: "00"
          },
          {
            type: "block",
            label: "Regular event",
          }
        ]
      });
    });

    function myNavFunction(id) {
      $("#date-popover").hide();
      var nav = $("#" + id).data("navigation");
      var to = $("#" + id).data("to");
      console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
    }
  </script>
</body>

</html>
