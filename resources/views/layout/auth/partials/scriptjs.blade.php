<script src="{{ asset('Auth/app.js') }}"></script>
<script src="{{ asset('Auth/build/js/intlTelInput.js') }}"></script>

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
<script>
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
      // allowDropdown: false,
      // autoHideDialCode: false,
      // autoPlaceholder: "off",
      // dropdownContainer: document.body,
       excludeCountries: ["us"],
      // formatOnDisplay: false,
       geoIpLookup: function(callback) {
         $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
           var countryCode = (resp && resp.country) ? resp.country : "";
           callback(countryCode);
         });
       },
      // hiddenInput: "full_number",
      initialCountry: "tg",
      // localizedCountries: { 'de': 'Deutschland' },
      // nationalMode: false,
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
      // placeholderNumberType: "MOBILE",
       preferredCountries: ['tg', 'ci'],
       separateDialCode: true,
      utilsScript: "{{ asset('Auth/build/js/utils.js') }}",
    });
  </script>
