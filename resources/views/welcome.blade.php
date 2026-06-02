<x-app-layout>
<!-- Slider Area Start -->
<section class="bleezy-slider-area">
    <div class="bleezy-slide">
        <div class="bleezy-main-slide slide-item-1">
            <div class="bleezy-main-caption">
                <div class="bleezy-caption-cell">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="slider-text">
                                    <h2>@lang('lang.vision')</h2>
                                    <p>@lang('lang.vision_describe').</p>
                                    <a href="{{ route('about', app()->getLocale()) }}" class="bleezy-btn">@lang('lang.about')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bleezy-main-slide slide-item-2">
            <div class="bleezy-main-caption">
                <div class="bleezy-caption-cell">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="slider-text">
                                    <h2>@lang('lang.mission', ['param'=>"s"])</h2>
                                    <p>@lang('lang.mission_describe').</p>
                                    <a href="{{ route('about', app()->getLocale()) }}" class="bleezy-btn">@lang('lang.about')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bleezy-main-slide slide-item-3">
            <div class="bleezy-main-caption">
                <div class="bleezy-caption-cell">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="slider-text">
                                    <h2>@lang('lang.values')</h2>
                                    <p>@lang('lang.values_describe').</p>
                                    <a href="{{ route('about', app()->getLocale()) }}" class="bleezy-btn">@lang('lang.about')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Slider Area End -->

<!-- Services Area Start -->
<section class="bleezy-service-area section_t_70 section_b_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="site-heading">
                    <h3>@lang('lang.what_we_offer')</h3>
                    <h2>@lang('lang.service', ['param'=>"s"])</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <div class="single-service">
                    <div class="service-icon">
                        <i class="flaticon-house-security"></i>
                    </div>
                    <h3><a>@lang('lang.home_security')</a></h3>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="single-service">
                    <div class="service-icon">
                        <i class="flaticon-security-camera"></i>
                    </div>
                    <h3><a>@lang('lang.camera')</a></h3>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="single-service">
                    <div class="service-icon">
                        <i class="flaticon-locked-internet-security-padlock"></i>
                    </div>
                    <h3><a>@lang('lang.alarm')</a></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <div class="single-service">
                    <div class="service-icon">
                        <i class="flaticon-computer"></i>
                    </div>
                    <h3><a>@lang('lang.training')</a></h3>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="single-service">
                    <div class="service-icon">
                        <i class="flaticon-policeman"></i>
                    </div>
                    <h3><a>@lang('lang.bodyguard')</a></h3>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="single-service">
                    <div class="service-icon">
                        <i class="flaticon-policeman"></i>
                    </div>
                    <h3><a>@lang('lang.event', ['param'=>'s'])</a></h3>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Area End -->  

<!-- Count Area Start -->
<section class="bleezy-count-area section_100">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="counts-text">
                    <h3>Nous sommes prêt à vous offrir des services de sécurité à des coûts raisonables</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <div class="count-box">
                    <h3 class="counter">1021</h3>
                    <h4>@lang('lang.agent', ['param'=>'s'])</h4>
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="count-box">
                    <h3 class="counter">1030</h3>
                    <h4>@lang('lang.employee', ['param'=>'s'])</h4>
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="count-box">
                    <h3 class="counter">520</h3>
                    <h4>@lang('lang.contract', ['param'=>'s'])</h4>
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="count-box">
                    <h3 class="counter">501</h3>
                    <h4>@lang('lang.partner', ['param'=>'s']) </h4>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Count Area End -->   

<!-- Testimonial Area Start -->
<section class="bleezy-testimonial-area section_100">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="site-heading-black">
                    <h3>Ce qu'ils disent de Nous</h3>
                    <h2>Temoignages</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="testimonial-slide">
                    <div class="single-testimonial">
                        <div class="testimonial-text">
                            <p>
                                En ce qui concerne la sécurité de votre personne, vos locaux ou tout autre service du genre, nous recommandons ‘’JAGUAR SECURITY SERVICES’’
S’ils ne sont pas les meilleurs, ils font partie des meilleurs en matière de sécurité privée en Guinée. En tout cas, nous, nous sommes convaincus et assurés de leur services.
                            </p>
                        </div>
                        <div class="testimonial-info">
                            <div class="info-img">
                                <img src="{{ asset('images/partners/pcg.png') }}" alt="client" />
                            </div>
                            <div class="info-name">
                                <h4>Pharmacie Centrale</h4>
                                <p>Administration Publique</p>
                            </div>
                        </div>
                    </div>
                    <div class="single-testimonial">
                        <div class="testimonial-text">
                            <p>
                                Nous avons douté tout au début, quand ils nous ont proposé leurs services. Nous étions presque sceptiques, mais avec le temps, nous les avons compris. Aujourd’hui, nous témoignons avec un cœur rempli de joie que ‘’JAGUAR SECURITRY SERVICES’’ est une entreprise de sécurité privée en Guinée qui travail avec professionnalisme mais avec assurance et engagement. En ce qui les concerne, nous pouvons bien dormir sans soucis majeurs, car nous estimons que nos installations se trouvent dans les mains d’une équipe très vigilante. 
                                Choisissez-les ! Vous ne serez pas déçus….
                            </p>
                        </div>
                        <div class="testimonial-info">
                            <div class="info-img">
                                <img src="{{ asset('images/partners/swb.png') }}" alt="client" />
                            </div>
                            <div class="info-name">
                                <h4>Sawaba</h4>
                                <p>Etablissement Privé</p>
                            </div>
                        </div>
                    </div>
                    <div class="single-testimonial">
                        <div class="testimonial-text">
                            <p>
                                On peinait vraiment à trouver tout au début une entreprise digne de nom. Mais suite à notre collaboration avec ‘’JAGUAR SECURITY SERVICES’’, nous les avons observés au début, nous étions un peu méfiants mais avec le temps, nous avons pu trouver le meilleur en Eux, car ils étaient plus que professionnels….
                            </p>
                        </div>
                        <div class="testimonial-info">
                            <div class="info-img">
                                <img src="{{ asset('images/partners/ong-mines.png') }}" alt="client" />
                            </div>
                            <div class="info-name">
                                <h4>ONG Mines</h4>
                                <p>Mines Sans Pauvreté</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Testimonial Area End -->   

<!-- Team Member Area Start -->
<section class="bleezy-team-member-area section_t_70 section_b_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="site-heading">
                    <h3>JAGUAR SECURITY SERVICES SARL</h3>
                    <h2>JSS TEAM</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="team-slider">
                    <div class="single-team-slide">
                        <div class="team-img">
                            <a>
                                <img src="{{ asset('images/team/ceo.webp') }}" alt="team img" />
                            </a>
                            <div class="team-social-box">
                                <div class="team-social">
                                    <a><i class="fa fa-facebook"></i></a>
                                    <a><i class="fa fa-twitter"></i></a>
                                    <a><i class="fa fa-linkedin"></i></a>
                                    <a><i class="fa fa-skype"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="team-text">
                            <h4><a>Moussa TOURE</a></h4>
                            <p>PDG</p>
                        </div>
                    </div>

                    <div class="single-team-slide">
                        <div class="team-img">
                            <a>
                                <img src="{{ asset('images/team/assistant_ceo.webp') }}" alt="team img" />
                            </a>
                            <div class="team-social-box">
                                <div class="team-social">
                                    <a><i class="fa fa-facebook"></i></a>
                                    <a><i class="fa fa-twitter"></i></a>
                                    <a><i class="fa fa-linkedin"></i></a>
                                    <a><i class="fa fa-skype"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="team-text">
                            <h4><a>Djalamba FADIGA</a></h4>
                            <p>ASSISTANTE PDG</p>
                        </div>
                    </div>

                    <div class="single-team-slide">
                        <div class="team-img">
                            <a>
                                <img src="{{ asset('images/team/accountant.webp') }}" alt="team img" />
                            </a>
                            <div class="team-social-box">
                                <div class="team-social">
                                    <a><i class="fa fa-facebook"></i></a>
                                    <a><i class="fa fa-twitter"></i></a>
                                    <a><i class="fa fa-linkedin"></i></a>
                                    <a><i class="fa fa-skype"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="team-text">
                            <h4><a>Richala TCHATAGBA</a></h4>
                            <p>COMPTABLE</p>
                        </div>
                    </div>

                    <div class="single-team-slide">
                        <div class="team-img">
                            <a>
                                <img src="{{ asset('images/team/secretary.webp') }}" alt="team img" />
                            </a>
                            <div class="team-social-box">
                                <div class="team-social">
                                    <a><i class="fa fa-facebook"></i></a>
                                    <a><i class="fa fa-twitter"></i></a>
                                    <a><i class="fa fa-linkedin"></i></a>
                                    <a><i class="fa fa-skype"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="team-text">
                            <h4><a>Rahmatoulaye DIALLO</a></h4>
                            <p>SECRETAIRE</p>
                        </div>
                    </div>
                    
                    <div class="single-team-slide">
                        <div class="team-img">
                            <a>
                                <img src="{{ asset('images/team/logistic_executive.webp') }}" alt="team img" />
                            </a>
                            <div class="team-social-box">
                                <div class="team-social">
                                    <a><i class="fa fa-facebook"></i></a>
                                    <a><i class="fa fa-twitter"></i></a>
                                    <a><i class="fa fa-linkedin"></i></a>
                                    <a><i class="fa fa-skype"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="team-text">
                            <h4><a>Aïcha CAMARA</a></h4>
                            <p>RESPONSABLE LOGISTIQUE</p>
                        </div>
                    </div>

                    <div class="single-team-slide">
                        <div class="team-img">
                            <a>
                                <img src="{{ asset('images/team/personal_manager.webp') }}" alt="team img" />
                            </a>
                            <div class="team-social-box">
                                <div class="team-social">
                                    <a><i class="fa fa-facebook"></i></a>
                                    <a><i class="fa fa-twitter"></i></a>
                                    <a><i class="fa fa-linkedin"></i></a>
                                    <a><i class="fa fa-skype"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="team-text">
                            <h4><a>Ousmane CAMARA</a></h4>
                            <p>RESSOURCE HUMAINE</p>
                        </div>
                    </div>

                    <div class="single-team-slide">
                        <div class="team-img">
                            <a>
                                <img src="{{ asset('images/team/operation_executive.webp') }}" alt="team img" />
                            </a>
                            <div class="team-social-box">
                                <div class="team-social">
                                    <a><i class="fa fa-facebook"></i></a>
                                    <a><i class="fa fa-twitter"></i></a>
                                    <a><i class="fa fa-linkedin"></i></a>
                                    <a><i class="fa fa-skype"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="team-text">
                            <h4><a>Sékouba BANGOURA</a></h4>
                            <p>DIRECTEUR DES OPERATIONS</p>
                        </div>
                    </div>

                    <div class="single-team-slide">
                        <div class="team-img">
                            <a>
                                <img src="{{ asset('images/team/trainer.webp') }}" alt="team img" />
                            </a>
                            <div class="team-social-box">
                                <div class="team-social">
                                    <a><i class="fa fa-facebook"></i></a>
                                    <a><i class="fa fa-twitter"></i></a>
                                    <a><i class="fa fa-linkedin"></i></a>
                                    <a><i class="fa fa-skype"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="team-text">
                            <h4><a>Abdoulaye SAMPAO</a></h4>
                            <p>FORMATEUR</p>
                        </div>
                    </div>
                    
                    <div class="single-team-slide">
                        <div class="team-img">
                            <a>
                                <img src="{{ asset('images/team/assistants/1.webp') }}" alt="team img" />
                            </a>
                            <div class="team-social-box">
                                <div class="team-social">
                                    <a><i class="fa fa-facebook"></i></a>
                                    <a><i class="fa fa-twitter"></i></a>
                                    <a><i class="fa fa-linkedin"></i></a>
                                    <a><i class="fa fa-skype"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="team-text">
                            <h4><a>Maïmouna SALL</a></h4>
                            <p>ASSISTANTE DE DIRECTION</p>
                        </div>
                    </div>
                    
                    <div class="single-team-slide">
                        <div class="team-img">
                            <a>
                                <img src="{{ asset('images/team/assistants/2.webp') }}" alt="team img" />
                            </a>
                            <div class="team-social-box">
                                <div class="team-social">
                                    <a><i class="fa fa-facebook"></i></a>
                                    <a><i class="fa fa-twitter"></i></a>
                                    <a><i class="fa fa-linkedin"></i></a>
                                    <a><i class="fa fa-skype"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="team-text">
                            <h4><a>Yéli CAMARA</a></h4>
                            <p>ASSISTANTE COMMERCIALE</p>
                        </div>
                    </div>
                    
                    <div class="single-team-slide">
                        <div class="team-img">
                            <a>
                                <img src="{{ asset('images/team/assistants/3.webp') }}" alt="team img" />
                            </a>
                            <div class="team-social-box">
                                <div class="team-social">
                                    <a><i class="fa fa-facebook"></i></a>
                                    <a><i class="fa fa-twitter"></i></a>
                                    <a><i class="fa fa-linkedin"></i></a>
                                    <a><i class="fa fa-skype"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="team-text">
                            <h4><a>Ismaël FOFANA</a></h4>
                            <p>ASSISTANT COMMERCIAL</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Team Member Area End -->

<!-- Blog Area Start -->
{{-- <section class="bleezy-blog-area section_b_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="site-heading">
                    <h3>JAGUAR SECURITY SERVICES SARL</h3>
                    <h2>Nos Evénements</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <div class="single-blog">
                    <div class="blog-image">
                        <a>
                            <img src="{{ asset('images/blog-1.jpeg') }}" alt="blog" />
                        </a>
                    </div>
                    <div class="blog-text">
                        <h2><a>Security System Of Any Building</a></h2>
                        <div class="blog-meta">
                            <p>-: Jan 20, 2018   /   Admin   /   6 Likes</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="single-blog">
                    <div class="blog-image">
                        <a>
                            <img src="{{ asset('images/blog-2.jpeg') }}" alt="blog" />
                        </a>
                    </div>
                    <div class="blog-text">
                        <h2><a>Don’t Worry Your Data is Safe</a></h2>
                        <div class="blog-meta">
                            <p>-: Jan 20, 2018   /   Admin   /   6 Likes</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="single-blog">
                    <div class="blog-image">
                        <a>
                            <img src="{{ asset('images/blog-3.jpeg') }}" alt="blog" />
                        </a>
                    </div>
                    <div class="blog-text">
                        <h2><a>Go next we are always with you</a></h2>
                        <div class="blog-meta">
                            <p>-: Jan 20, 2018   /   Admin   /   6 Likes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}
<!-- Blog Area End -->   

<!-- Broucher Area Start -->
<section class="bleezy-broucher-area">
    <div class="broucher-overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="broucher-left">
                    <h3>@lang('lang.technical_offer_title')</h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="broucher-right">
                    <div class="download-btn">
                        <a href="{{ asset('others/offer.pdf') }}">@lang('lang.offer').Pdf <span class="fa fa-arrow-circle-o-down"></span></a>
                        <i class="fa fa-file-pdf-o"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Broucher Area End -->
</x-app-layout>