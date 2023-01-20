<aside>
    <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
            {{-- <p class="centered"><a href="{{ url('profil') }}"><img src="img/ui-sam.jpg" class="img-circle" width="80">
            <h5 class="centered">Utilisateur</h5>
            </a></p> --}}
            <li class="mt">
                <a class="active" href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Accueil</span>
                </a>
            </li>

            <li class="sub-menu">
                <a href="javascript:;">
                    <i class="fa fa-desktop"></i>
                    <span> Paramétrage </span>
                </a>
                <ul class="sub">
                    <li><a href="{{ url('Configuration') }}"> Configuration</a></li>
                </ul>
                <ul class="sub">
                    <li><a href="{{ route('paie_form') }}"> Paiement</a></li>
                </ul>
                <ul class="sub">
                    <li><a href="{{ url('Liste_Etat') }}"> Liste Etat</a></li>
                </ul>
            </li>

        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>