<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale=1">
    <title> Make-IT</title>
    <link rel="icon" type="image/png" href="{{ asset('/logo/logo.png') }}" />
    <link href="{{ asset('/VitrineTemp/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/VitrineTemp/css/style.cs') }}s" rel="stylesheet" type="text/css">
    <link href="{{ asset('/VitrineTemp/css/linecons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/VitrineTemp/css/font-awesome.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/VitrineTemp/css/responsive.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/VitrineTemp/css/animate.css') }}" rel="stylesheet" type="text/css">

    <link href='http://fonts.googleapis.com/css?family=Lato:400,900,700,700italic,400italic,300italic,300,100italic,100,900italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Dosis:400,500,700,800,600,300,200' rel='stylesheet' type='text/css'>

    <!--[if IE]><style type="text/css">.pie {behavior:url(PIE.htc);}</style><![endif]-->

    <script type="text/javascript" src="{{ asset('/VitrineTemp/js/jquery.1.8.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/VitrineTemp/js/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/VitrineTemp/js/jquery-scrolltofixed.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/VitrineTemp/js/jquery.easing.1.3.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/VitrineTemp/js/jquery.isotope.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/VitrineTemp/js/wow.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/VitrineTemp/js/classie.js') }}"></script>

    <!--[if lt IE 9]>
    <script src="js/respond-1.1.0.min.js"></script>
    <script src="js/html5shiv.js"></script>
    <script src="js/html5element.js"></script>
<![endif]-->

    <script type="text/javascript">
        $(document).ready(function(e) {
            $('.res-nav_click').click(function() {
                $('ul.toggle').slideToggle(600)
            });

            $(document).ready(function() {
                $(window).bind('scroll', function() {
                    if ($(window).scrollTop() > 0) {
                        $('#header_outer').addClass('fixed');
                    } else {
                        $('#header_outer').removeClass('fixed');
                    }
                });

            });


        });

        function resizeText() {
            var preferredWidth = 767;
            var displayWidth = window.innerWidth;
            var percentage = displayWidth / preferredWidth;
            var fontsizetitle = 25;
            var newFontSizeTitle = Math.floor(fontsizetitle * percentage);
            $(".divclass").css("font-size", newFontSizeTitle)
        }
    </script>
</head>

<body>

    <!--Header_section-->
    <header id="header_outer">
        <div class="container">
            <div class="header_section">
                <div class="logo"><a href="javascript:void(0)"><img src="{{ asset('/VitrineTemp/img/logo.png') }}" height="50" width="50" alt=""></a></div>
                <nav class="nav" id="nav">
                    <ul class="toggle">
                        <li><a href="#top_content">Accueil</a></li>
                        <li><a href="#service">Comment ca marche</a></li>
                        <li><a href="#client_outer">Clients</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="{{ url('auth/inscrit1') }}">S'inscrire</a></li>
                    </ul>
                    <ul class="">
                        <li><a href="#top_content">Accueil</a></li>
                        <li><a href="#service">Comment ca marche</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="{{ url('auth/inscrit1') }}">S'inscrire</a></li>
                    </ul>
                </nav>
                <a class="res-nav_click animated wobble wow" href="javascript:void(0)"><i class="fa-bars"></i></a>
            </div>
        </div>
    </header>
    <!--Header_section-->

    <!--Top_content-->
    <section id="top_content" class="top_cont_outer">
        <div class="top_cont_inner">
            <div class="container">
                <div class="top_content">
                    <div class="row">
                        <div class="col-lg-5 col-sm-7">
                            <div class="top_left_cont flipInY wow animated">
                                <h2>make IT </h2>

                                <h3> site de MAKE-IT pour tout comprendre et tout savoir sur le marketing et le réseaux
                                    pyramidale!</h3>
                                <p> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Deleniti laudantium rem
                                    qui in a eos neque! Recusandae deserunt, dolore quo dolorem eos dolor illo
                                    laudantium eaque dicta error repellat earum. </p>
                                <a href="#service" class="learn_more2"> Plus d'informations</a>
                            </div>
                        </div>
                        <div class="col-lg-7 col-sm-5"> </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Top_content-->

    <!--Service-->
    <section id="service">
        <div class="container">
            <h2> informations</h2>
            <div class="service_area">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="service_block">
                            <div class="service_icon delay-03s animated wow  zoomIn"> <span><i class="fa-flash"></i></span> </div>
                            <h3 class="animated fadeInUp wow">Comment ca marche ? </h3>
                            <p class="animated fadeInDown wow">
                                Tout d’abord, Make it est un système de cotisation et d’entre-aide. Un système facile à
                                comprendre et à intégrer à moindre coût. C’est un réseau financière très simple et
                                moderne basé sur la matrice forcé 2X5.

                                Il comprend 3 phases de 4 niveaux et 2 phases de 3 niveaux. Tout adhérent a son propre
                                compte back-office où il peut suivre en toute transrance l’évolution de son compte.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="service_block">
                            <div class="service_icon icon2  delay-03s animated wow zoomIn"> <span><i class="fa-lock"></i></span> </div>
                            <h3 class="animated fadeInUp wow">Inscription</h3>
                            <p class="animated fadeInDown wow">
                                Pour être membre de ce système, il suffit d’avoir 3000f CFA sur un compte mobile money
                                de votre réseau téléphonique et de l’envoyer aux: Vous envoyez votre cotisation sur la
                                syntaxe de Make-it selon votre moyen d’opération choisi Mobile Money. Vous recevrez un
                                code de référent dans le message juste après la transaction qui va vous servir à faire
                                votre inscription.

                                Maintenant que vous êtes inscrit vous avez l’obligation de parrainer deux (2) personnes,
                                pour activer votre compte.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="service_block">
                            <div class="service_icon icon3  delay-03s animated wow zoomIn"> <span><i class="fa-shield"></i></span> </div>
                            <h3 class="animated fadeInUp wow">Description</h3>
                            <p class="animated fadeInDown wow">A chaque phase se trouve une matrice à valider par le
                                membre pour bénéficier des avantages liés à cette phase. La validation de chaque phase
                                résulte du travail de groupe de tous les membres.

                                Le gain de chaque phase est perçu lorsqu’on active la phase suivante.

                                NB: La somme minimum retirable est de 2000f CFA</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Service-->

    <!--main-section-end-->

    <!--new_portfolio-->

    <!-- Portfolio -->

    <!--/Portfolio -->

    <!--new_portfolio-->

    <!--
<section class="main-section paddind" id="Portfolio">
 <div class="container">
    <h2>Portfolio</h2>
    <h6>Fresh portfolio of designs that will keep you wanting more.</h6>
 </div>


</section>

-->


    <!--main-section client-part-end-->

    <div class="c-logo-part">
        <!--c-logo-part-start-->
        <div class="container">
            <ul class="delay-06s animated  bounce wow">
                <li><a href="javascript:void(0)"><img src="img/c-liogo1.png" alt=""></a></li>
                <li><a href="javascript:void(0)"><img src="img/c-liogo2.png" alt=""></a></li>
                <li><a href="javascript:void(0)"><img src="img/c-liogo3.png" alt=""></a></li>
                <li><a href="javascript:void(0)"><img src="img/c-liogo5.png" alt=""></a></li>
            </ul>
        </div>
    </div>
    <!--c-logo-part-end-->

    <!--main-section team-end-->


    <!--twitter-feed-end-->
    <footer class="footer_section" id="contact">
        <div class="container">
            <section class="main-section contact" id="contact">
                <div class="contact_section">
                    <h2>CONTACTEZ-NOUS</h2>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="contact_block">
                                <div class="contact_block_icon rollIn animated wow"><span><i class="fa-home"></i></span></div>
                                <span> Lomé <br>
                                    TOGO </span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="contact_block">
                                <div class="contact_block_icon icon2 rollIn animated wow"><span><i class="fa-phone"></i></span></div>
                                <span> (228) 00 01 22 22 </span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="contact_block">
                                <div class="contact_block_icon icon3 rollIn animated wow"><span><i class="fa-pencil"></i></span></div>
                                <span> <a href="mailto:hello@butterfly.com"> dgmakeit@gmail.com </a> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 wow fadeInLeft">
                        <div class="contact-info-box address clearfix">
                            <h3>N'hesitez pas à nous écrire pour plus d'informations!</h3>
                            <p>Accusantium quam, aliquam ultricies eget tempor id, aliquam eget nibh et. Maecen aliquam,
                                risus at semper. Accusantium quam, aliquam ultricies eget tempor id, aliquam eget nibh
                                et. Maecen aliquam, risus at semper.</p>
                            <p>Accusantium quam, aliquam ultricies eget tempor id, aliquam eget nibh et. Maecen
                                aliquampor id.</p>
                        </div>
                        <ul class="social-link">
                            <li class="twitter animated bounceIn wow delay-02s"><a href="javascript:void(0)"><i class="fa-twitter"></i></a></li>
                            <li class="facebook animated bounceIn wow delay-03s"><a href="javascript:void(0)"><i class="fa-facebook"></i></a></li>
                            <li class="pinterest animated bounceIn wow delay-04s"><a href="javascript:void(0)"><i class="fa-pinterest"></i></a></li>
                            <li class="gplus animated bounceIn wow delay-05s"><a href="javascript:void(0)"><i class="fa-google-plus"></i></a></li>
                            <li class="dribbble animated bounceIn wow delay-06s"><a href="javascript:void(0)"><i class="fa-dribbble"></i></a></li>
                        </ul>
                    </div>
                    <div class="col-lg-6 wow fadeInUp delay-06s">
                        <div class="form">
                            <input class="input-text animated wow flipInY delay-02s" type="text" name="" value="nom  d'utilisateur *" onFocus="if(this.value==this.defaultValue)this.value='';" onBlur="if(this.value=='')this.value=this.defaultValue;">
                            <input class="input-text animated wow flipInY delay-04s" type="text" name="" value="Email *" onFocus="if(this.value==this.defaultValue)this.value='';" onBlur="if(this.value=='')this.value=this.defaultValue;">
                            <textarea class="input-text text-area animated wow flipInY delay-06s" cols="0" rows="0" onFocus="if(this.value==this.defaultValue)this.value='';" onBlur="if(this.value=='')this.value=this.defaultValue;">Votre message ici *</textarea>
                            <input class="input-btn animated wow flipInY delay-08s" type="submit" value="Envoyer">
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="container">
            <div class="footer_bottom"> <span>Copyright © {{ date('Y') }} </span> </div>
        </div>
    </footer>
    <script type="text/javascript">
        $(document).ready(function(e) {
            $('#header_outer').scrollToFixed();
            $('.res-nav_click').click(function() {
                $('.main-nav').slideToggle();
                return false

            });

        });
    </script>
    <script>
        wow = new WOW({
            animateClass: 'animated',
            offset: 100
        });
        wow.init();
        document.getElementById('').onclick = function() {
            var section = document.createElement('section');
            section.className = 'wow fadeInDown';
            section.className = 'wow shake';
            section.className = 'wow zoomIn';
            section.className = 'wow lightSpeedIn';
            this.parentNode.insertBefore(section, this);
        };
    </script>
    <script type="text/javascript">
        $(window).load(function() {

            $('a').bind('click', function(event) {
                var $anchor = $(this);

                $('html, body').stop().animate({
                    scrollTop: $($anchor.attr('href')).offset().top - 91
                }, 1500, 'easeInOutExpo');
                /*
                if you don't want to use the easing effects:
                $('html, body').stop().animate({
                	scrollTop: $($anchor.attr('href')).offset().top
                }, 1000);
                */
                event.preventDefault();
            });
        })
    </script>

    <!--<script type="text/javascript">
        $(window).load(function() {


            var $container = $('.portfolioContainer'),
                $body = $('body'),
                colW = 350,
                columns = null;


            $container.isotope({
                // disable window resizing
                resizable: true,
                masonry: {
                    columnWidth: colW
                }
            });

            $(window).smartresize(function() {
                // check if columns has changed
                var currentColumns = Math.floor(($body.width() - 30) / colW);
                if (currentColumns !== columns) {
                    // set new column count
                    columns = currentColumns;
                    // apply width to container manually, then trigger relayout
                    $container.width(columns * colW)
                        .isotope('reLayout');
                }

            }).smartresize(); // trigger resize to set container width
            $('.portfolioFilter a').click(function() {
                $('.portfolioFilter .current').removeClass('current');
                $(this).addClass('current');

                var selector = $(this).attr('data-filter');
                $container.isotope({

                    filter: selector,
                });
                return false;
            });

        });
    </script>


-->

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Portfolio Isotope
            var container = $('#portfolio-wrap');


            container.isotope({
                animationEngine: 'best-available',
                animationOptions: {
                    duration: 200,
                    queue: false
                },
                layoutMode: 'fitRows'
            });

            $('#filters a').click(function() {
                $('#filters a').removeClass('active');
                $(this).addClass('active');
                var selector = $(this).attr('data-filter');
                container.isotope({
                    filter: selector
                });
                setProjects();
                return false;
            });


            function splitColumns() {
                var winWidth = $(window).width(),
                    columnNumb = 1;


                if (winWidth > 1024) {
                    columnNumb = 4;
                } else if (winWidth > 900) {
                    columnNumb = 2;
                } else if (winWidth > 479) {
                    columnNumb = 2;
                } else if (winWidth < 479) {
                    columnNumb = 1;
                }

                return columnNumb;
            }

            function setColumns() {
                var winWidth = $(window).width(),
                    columnNumb = splitColumns(),
                    postWidth = Math.floor(winWidth / columnNumb);

                container.find('.portfolio-item').each(function() {
                    $(this).css({
                        width: postWidth + 'px'
                    });
                });
            }

            function setProjects() {
                setColumns();
                container.isotope('reLayout');
            }

            container.imagesLoaded(function() {
                setColumns();
            });


            $(window).bind('resize', function() {
                setProjects();
            });

        });
        $(window).load(function() {
            jQuery('#all').click();
            return false;
        });
    </script>
</body>

</html>