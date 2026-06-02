<x-app-layout>

<!-- Breadcromb Area Start -->
<section class="bleezy-breadcromb-area">
    <div class="breadcromb-top section_50">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcromb-top-text">
                        <h2>@lang('lang.contact', array('param'=>"s"))</h2>
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
                            <li>@lang('lang.contact', array('param'=>"s"))</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcromb Area End -->

<!-- Contact Page Area Start -->
<section class="bleezy-contact-page-area section_t_70 section_b_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="site-heading">
                    <h3>@lang('lang.contact_title')</h3>
                    <p>@lang('lang.contact_subtitle').</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 col-sm-4">
                <div class="single-contact-address">
                    <h3>Conakry, Guinea</h3>
                    <ul>
                        <li>
                            <i class="fa fa-map-marker"></i>
                            <p>Aéroport Ahmed Sékou Touré, Gbessia</p>
                        </li>
                        <li>
                            <i class="fa fa-phone"></i>
                            <p>+212 607-869004</p>
                        </li>
                        <li>
                            <i class="fa fa-envelope-o"></i>
                            <p>jaguar28jss@gmail.com</p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-7">
                <div class="contact-form">
                    <form>
                        <div class="row">
                            <div class="col-md-12">
                                <p><input type="tel" name="phone" placeholder="@lang('lang.phone_id')" required></p>
                                <p><textarea name="Message" placeholder="@lang('lang.write_message')" style="resize: none"></textarea></p>

                                <div class="contact-form-button">
                                    <button>@lang('lang.submit')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Page Area End -->

<!-- Gmap Start -->
<div class="map-canvas" data-zoom="12" data-lat="-37.817085" data-lng="144.955631" data-type="roadmap" data-hue="#ffc400" data-title="Envato" data-icon-path="{{ asset('images/marker.png') }}" data-content="Melbourne VIC 3000, Australia<br><a href='mailto:info@youremail.com'>info@youremail.com</a>"></div>
<!-- Gmap Start -->

@push('js-view')
<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBf4UvvTN2QLWT4BiewE_3fEzK3QrRsLJE"></script>
@endpush
</x-app-layout>


