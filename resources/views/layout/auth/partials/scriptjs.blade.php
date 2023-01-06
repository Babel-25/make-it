<script src="{{ asset('Auth/app.js') }}"></script>

<script type="text/javascript">
    $("document").ready(function() {
        setTimeout(function() {

            $("div.alert").remove();

        }, 5000);
    });
</script>
<script>
    $(function() {
        var date = new Date();
        var mois = date.getMonth() + 1;
        var jours = date.getDate();
        var annee = date.getFullYear();
        if (mois < 10) {

            mois = '0' + mois.toString();
        }
        if (jours < 10) {

            jours = '0' + jours.toString();
        }
        var maxdate = annee + '-' + mois + '-' + jours;
        document.getElementById("dateValidate").setAttribute('max', maxdate);
    });
</script>
<script>
    $(function() {
        var $sections = $('.form-section');

        function navigateTo(index) {

            $sections.removeClass('current').eq(index).addClass('current');

            $('.form-navigation .previous').toggle(index > 0);
            var atTheEnd = index >= $sections.length - 1;
            $('.form-navigation .next').toggle(!atTheEnd);
            $('.form-navigation [Type=submit]').toggle(atTheEnd);


            const step = document.querySelector('.step' + index);
            step.style.backgroundColor = "#17a2b8";
            step.style.color = "white";

        }

        function curIndex() {

            return $sections.index($sections.filter('.current'));
        }

        $('.form-navigation .previous').click(function() {
            navigateTo(curIndex() - 1);
        });

        $('.form-navigation .next').click(function() {
            $('.sign-in-form').parsley().whenValidate({
                group: 'block-' + curIndex()
            }).done(function() {
                navigateTo(curIndex() + 1);
            });

        });

        $sections.each(function(index, section) {
            $(section).find(':input').attr('data-parsley-group', 'block-' + index);
        });

        navigateTo(0);

    });
</script>
