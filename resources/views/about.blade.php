<x-app-layout>

<!-- Breadcromb Area Start -->
<section class="bleezy-breadcromb-area">
    <div class="breadcromb-top section_50">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcromb-top-text">
                        <h2 class="text-uppercase">@lang('lang.about') JAGUAR SECURITY SERVICES SARL</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="breadcromb-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcromb-bottom-text">
                        <ul>
                            <li><a href="{{ route('welcome') }}">@lang('lang.home')</a></li>
                            <li><a><i class="fa fa-long-arrow-right"></i></a></li>
                            <li>@lang('lang.about')</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcromb Area End -->



<!-- About Page Start -->
<section class="bleezy-about-page section_t_70 section_b_70">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="about-left">
                    <h2>JAGUAR SECURITY SERVICES SARL</h2>
                    <p style="text-align: justify;">@lang('lang.company_resume').</p>
                    <p style="text-align: justify;">@lang('lang.company_describe').</p>
                    <!-- <a href="#" class="bleezy-btn">Read more</a> -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="about-right">
                    <img src="{{ asset('images/abt-img.png') }}" alt="about image" />
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About Page End -->

<!-- Statement Area Start -->
<section class="bleezy-statement-area section_b_70">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <div class="statement-left">
                    <h2>@lang('lang.mission', ['param'=>"s"])</h2>
                    <p style="text-align: justify;">@lang('lang.mission_describe').</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="statement-right">
                    <h2>@lang('lang.vision')</h2>
                    <p style="text-align: justify;">@lang('lang.vision_describe').</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="statement-right">
                    <h2>@lang('lang.values')</h2>
                    <p style="text-align: justify;">@lang('lang.values_describe').</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Statement Area End -->
</x-app-layout>
