<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" href="{{ asset('/logo/logo.png') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('authTemp/style.css') }}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <title> Inscription </title>


</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <div class="row justify-content-md-center">
                    <div class="col-md-12 ">
                        <div class=" px-5 py-3 mt-5 ">
                            <h1 class="text-danger text-center mt-3 mb-4">Incription</h1>
                            <div>
                                @if(session()->exists('message'))
                                <div class="alert alert-success" id="alert" align="center">
                                    
                                    {{session('message')}}

                                </div>
                                @endif
                            </div>
                            <div class="nav nav-fill my-3">
                                <label class="nav-link shadow-sm step0    border ml-2 ">Etape un</label>
                                <label class="nav-link shadow-sm step1   border ml-2 ">Etape deux</label>
                                <label class="nav-link shadow-sm step2   border ml-2 ">Etape Trois</label>
                            </div>

                            <form action="{{ route('register_action') }}" method="POST" class="sign-in-form">
                                @csrf
                                <div class="form-section">
                                    <label for="">Code paiemment:</label>
                                    <input type="text" class="form-control mb-3" value="{{old('codePai')}}" name="codePai">
                                    <span style="color:red">
                                        @error('codePai')
                                        <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </span>
                                    <!--<label for="">Code de parrainage:</label>
                                    <input type="text" class="form-control mb-3" value="{{old('codePar')}}" name="codePar">
                                    <span style="color:red">
                                        @error('codePar')
                                        <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </span>-->
                                    <label for="">Nom et Prénom:</label>
                                    <input type="text" class="form-control mb-3" value="{{old('name')}}" name="name">
                                    <span style="color:red">
                                        @error('name')
                                        <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </span>
                                    <label for="">Adresse:</label>
                                    <input type="text" class="form-control mb-3" value="{{old('adresse')}}" name="adresse">
                                    <span style="color:red">
                                        @error('adresse')
                                        <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </span>
                                    <label for="">Sexe:</label>
                                    <select name="sexe" class="form-control mb-3" value="{{old('sexe')}}">
                                        <p hidden>
                                            {{$gendre = \App\Models\Sexe::all()}}
                                        </p>
                                        <option></option>
                                        @foreach($gendre as $sexe)
                                        <option value=" {{$sexe->id}} ">{{$sexe->code}}</option>
                                        @endforeach
                                    </select>
                                    <span style="color:red">
                                        @error('sexe')
                                        <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </span>
                                </div>
                                <div class="form-section">
                                    <label for="">Contact:</label>
                                    <input type="tel" class="form-control mb-3" value="{{old('phone')}}" name="phone">
                                    <span style="color:red">
                                        @error('phone')
                                        <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </span>
                                    <label for="">Date de naissance:</label>
                                    <input type="date" class="form-control mb-3" id="dateValidate" value="{{old('date')}}" name="date">
                                    <span style="color:red">
                                        @error('date')
                                        <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </span>
                                    <label for="">E-mail:</label>
                                    <input type="email" class="form-control mb-3" value="{{old('email')}}" name="email">
                                    <span style="color:red">
                                        @error('email')
                                        <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </span>
                                </div>
                                <div class="form-section">
                                    <label for="">Identifiant:</label>
                                    <input type="text" class="form-control mb-3" value="{{old('pseudo')}}" name="pseudo">
                                    <span style="color:red">
                                        @error('pseudo')
                                        <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </span>
                                    <label for="password">Mot de passe:</label>
                                    <input type="password" class="form-control mb-3" name="password">
                                    <span style="color:red">
                                        @error('password')
                                        <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </span>
                                    <label for="passwordConf">Confirmer mot de passe:</label>
                                    <input type="password" class="form-control mb-3" name="passwordConf">
                                    <span style="color:red">
                                        @error('passwordConf')
                                        <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </span>
                                    <p id="message"></p>
                                    <input type="text" class="form-control mb-3" name="status" value="Client" hidden>
                                    <input type="text" class="form-control mb-3" name="etat" value="2" hidden>
                                </div>
                                <div class="form-navigation mt-3">
                                    <button type="button" class="previous btn btn-primary float-left">&lt; Précédent</button>
                                    <button type="button" class="next btn btn-primary float-right">Suivant &gt;</button>
                                    <button type="submit" class="btn btn-success float-right">Valider</button>
                                </div>

                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>A SAVOIR</h3>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam quas vel rerum. Veniam quisquam
                        facere, cumque, voluptas ratione doloribus laudantium ipsum dicta optio ut rerum rem nulla qui
                        consequuntur voluptatibus!
                    </p>
                    <button class="btn"><a href="{{ url('/connexion') }}" style="text-decoration: none; color:white;">
                            Se connecter
                        </a>
                    </button>
                </div>

                <img src="{{ asset('authTemp/img/log.svg') }}" class="image" alt="" />
            </div>
        </div>
    </div>

    <script src="{{ asset('authTemp/app.js') }}"></script>
    <script type="text/javascript">
        $("document").ready(function() {
            setTimeout(function() {

                $("div.alert").remove();

            },5000);
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
</body>

</html>
