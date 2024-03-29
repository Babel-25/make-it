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
                        <div>
                            @if (session()->exists('message7'))
                            <div class="alert alert-success" id="alert" align="center">
                                {{ session('message7') }}
                            </div>
                            @endif
                        </div>
                        <form action="{{ route('mailEnvoie')}}" method="POST">
                            @csrf
                            <input class="input-text animated wow flipInY delay-02s" type="text" name="name" value="nom  d'utilisateur *" onFocus="if(this.value==this.defaultValue)this.value='';" onBlur="if(this.value=='')this.value=this.defaultValue;">
                            <input class="input-text animated wow flipInY delay-04s" type="email" name="email" value="Email *" onFocus="if(this.value==this.defaultValue)this.value='';" onBlur="if(this.value=='')this.value=this.defaultValue;">
                            <textarea class="input-text text-area animated wow flipInY delay-06s" name="message" cols="0" rows="0" onFocus="if(this.value==this.defaultValue)this.value='';" onBlur="if(this.value=='')this.value=this.defaultValue;">Votre message ici *</textarea>
                            <button class="input-btn animated wow flipInY delay-08s" type="submit"> Envoyer </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="container">
        <div class="footer_bottom"> <span>Copyright © {{ date('Y') }} </span> </div>
    </div>
</footer>