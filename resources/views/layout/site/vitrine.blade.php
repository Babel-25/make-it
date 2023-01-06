<!doctype html>
<html>

<head>
    @include('layout.site.partials.head')
</head>

<body>

    <!--Header_section-->
    @include('layout.site.partials.header')
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
                            <div class="service_icon delay-03s animated wow  zoomIn"> <span><i
                                        class="fa-flash"></i></span> </div>
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
                            <div class="service_icon icon2  delay-03s animated wow zoomIn"> <span><i
                                        class="fa-lock"></i></span> </div>
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
                            <div class="service_icon icon3  delay-03s animated wow zoomIn"> <span><i
                                        class="fa-shield"></i></span> </div>
                            <h3 class="animated fadeInUp wow">Description</h3>
                            <p class="animated fadeInDown wow">A chaque phase se trouve une matrice à valider par le
                                membre pour bénéficier des avantages liés à cette phase. La validation de chaque phase
                                résulte du travail de groupe de tous les membres.

                                Le gain de chaque phase est perçu lorsqu’on active la phase suivante.

                                NB: La somme minimum retirable est de <b>2000 F CFA</b> </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


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

    <!-- Footer -->
    @include('layout.site.partials.footer')

    @include('layout.site.partials.scriptjs')


</body>

</html>
