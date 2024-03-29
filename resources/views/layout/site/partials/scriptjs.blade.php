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
<script type="text/javascript">
    $("document").ready(function() {
        setTimeout(function() {

            $("div.alert").remove();

        }, 7000);
    });
</script>