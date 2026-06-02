<x-app-layout>
<!-- Breadcromb Area Start -->
<section class="bleezy-breadcromb-area">
    <div class="breadcromb-top section_50">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcromb-top-text">
                        <h2>@lang('lang.service', array('param'=>"s"))</h2>
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
                            <li>@lang('lang.service', array('param'=>"s"))</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcromb Area End -->



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
                    {{-- <p>Enim ad minim veniam quis nostrud exercitation ullamco laboris aliquip dolor in velit esse cillum.</p> --}}
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="single-service">
                    <div class="service-icon">
                        <i class="flaticon-security-camera"></i>
                    </div>
                    <h3><a>@lang('lang.camera')</a></h3>
                    {{-- <p>Enim ad minim veniam quis nostrud exercitation ullamco laboris aliquip dolor in velit esse cillum.</p> --}}
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="single-service">
                    <div class="service-icon">
                        <i class="flaticon-locked-internet-security-padlock"></i>
                    </div>
                    <h3><a>@lang('lang.alarm')</a></h3>
                    {{-- <p>Enim ad minim veniam quis nostrud exercitation ullamco laboris aliquip dolor in velit esse cillum.</p> --}}
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
                    {{-- <p>Enim ad minim veniam quis nostrud exercitation ullamco laboris aliquip dolor in velit esse cillum.</p> --}}
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="single-service">
                    <div class="service-icon">
                        <i class="flaticon-policeman"></i>
                    </div>
                    <h3><a>@lang('lang.bodyguard')</a></h3>
                    {{-- <p>Enim ad minim veniam quis nostrud exercitation ullamco laboris aliquip dolor in velit esse cillum.</p> --}}
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="single-service">
                    <div class="service-icon">
                        <i class="flaticon-policeman"></i>
                    </div>
                    <h3><a>@lang('lang.event', ['param'=>'s'])</a></h3>
                    {{-- <p>Enim ad minim veniam quis nostrud exercitation ullamco laboris aliquip dolor in velit esse cillum.</p> --}}
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Area End -->

{{-- <section class="bleezy-pricing-area section_b_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="site-heading">
                    <h3>Catalogues</h3>
                    <h2>Nos Plans Tarifaires</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3  col-sm-6">
                <div class="single-pricing-table">
                    <div class="pricing-header">
                        <div class="pricing-heading">
                            <h3>Home Guard</h3>
                        </div>
                        <div class="price-value">
                            <p>$<span>120</span><span class="month">/mo</span></p>
                        </div>
                    </div>
                    <div class="pricing-desc">
                        <ul>
                            <li>
                                <i class="fa fa-check"></i>
                                1 Security Guard
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                1 User Only
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                 2 CC Camera 
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Change on Complain
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                24/7 Support
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Backup Support
                            </li>
                        </ul>
                    </div>
                    <div class="pricing-order">
                        <a href="#" class="bleezy-btn">order now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3  col-sm-6">
                <div class="single-pricing-table">
                    <div class="pricing-header">
                        <div class="pricing-heading">
                            <h3>Apartment Security</h3>
                        </div>
                        <div class="price-value">
                            <p>$<span>260</span><span class="month">/mo</span></p>
                        </div>
                    </div>
                    <div class="pricing-desc">
                        <ul>
                            <li>
                                <i class="fa fa-check"></i>
                                1 Security Guard
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                1 User Only
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                2 CC Camera
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Change on Complain
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                24/7 Online Support
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Backup Support
                            </li>
                        </ul>
                    </div>
                    <div class="pricing-order">
                        <a href="#" class="bleezy-btn">order now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3  col-sm-6">
                <div class="single-pricing-table">
                    <div class="pricing-header">
                        <div class="pricing-heading">
                            <h3>security Team</h3>
                        </div>
                        <div class="price-value">
                            <p>$<span>450</span><span class="month">/mo</span></p>
                        </div>
                    </div>
                    <div class="pricing-desc">
                        <ul>
                            <li>
                                <i class="fa fa-check"></i>
                                5 Man Security Team
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Max 2 user Only
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                 1 Gun Man 
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Change on Complain
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                24/7 Support
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Backup Support
                            </li>
                        </ul>
                    </div>
                    <div class="pricing-order">
                        <a href="#" class="bleezy-btn">order now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3  col-sm-6">
                <div class="single-pricing-table">
                    <div class="pricing-header">
                        <div class="pricing-heading">
                            <h3>Night Guard</h3>
                        </div>
                        <div class="price-value">
                            <p>$<span>300</span><span class="month">/mo</span></p>
                        </div>
                    </div>
                    <div class="pricing-desc">
                        <ul>
                            <li>
                                <i class="fa fa-check"></i>
                                2 Night Guard
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                1 User Only
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                               8 Hour in Night
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                               Change on Complain
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                24/7 Support
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Backup Support
                            </li>
                        </ul>
                    </div>
                    <div class="pricing-order">
                        <a href="#" class="bleezy-btn">order now</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3  col-sm-6">
                <div class="single-pricing-table">
                    <div class="pricing-header">
                        <div class="pricing-heading">
                            <h3>dog squad</h3>
                        </div>
                        <div class="price-value">
                            <p>$<span>320</span><span class="month">/mo</span></p>
                        </div>
                    </div>
                    <div class="pricing-desc">
                        <ul>
                            <li>
                                <i class="fa fa-check"></i>
                                4 Dog
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                1 Guardian
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                2 User Only
                            </li>
                            <li>
                                <i class="fa fa-times"></i>
                                Change on Complain
                            </li>
                            <li>
                                <i class="fa fa-times"></i>
                                24/7 Support
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Backup Support
                            </li>
                        </ul>
                    </div>
                    <div class="pricing-order">
                        <a href="#" class="bleezy-btn">order now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3  col-sm-6">
                <div class="single-pricing-table">
                    <div class="pricing-header">
                        <div class="pricing-heading">
                            <h3>Gate Man</h3>
                        </div>
                        <div class="price-value">
                            <p>$<span>160</span><span class="month">/mo</span></p>
                        </div>
                    </div>
                    <div class="pricing-desc">
                        <ul>
                            <li>
                                <i class="fa fa-check"></i>
                                3 Gate Man
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                with 1 gun
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Max 2 User
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Change on Complain
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                24/7 Support
                            </li>
                            <li>
                                <i class="fa fa-times"></i>
                               Backup Support
                            </li>
                        </ul>
                    </div>
                    <div class="pricing-order">
                        <a href="#" class="bleezy-btn">order now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3  col-sm-6">
                <div class="single-pricing-table">
                    <div class="pricing-header">
                        <div class="pricing-heading">
                            <h3>Gun Man</h3>
                        </div>
                        <div class="price-value">
                            <p>$<span>300</span><span class="month">/mo</span></p>
                        </div>
                    </div>
                    <div class="pricing-desc">
                        <ul>
                            <li>
                                <i class="fa fa-check"></i>
                                2 Gun Man
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                1 User Only
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                               Change on Complain
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                24/7 Support
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Backup Support
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Our Risk
                            </li>
                        </ul>
                    </div>
                    <div class="pricing-order">
                        <a href="#" class="bleezy-btn">order now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3  col-sm-6">
                <div class="single-pricing-table">
                    <div class="pricing-header">
                        <div class="pricing-heading">
                            <h3>bodyguard</h3>
                        </div>
                        <div class="price-value">
                            <p>$<span>270</span><span class="month">/mo</span></p>
                        </div>
                    </div>
                    <div class="pricing-desc">
                        <ul>
                            <li>
                                <i class="fa fa-check"></i>
                                2 bodyguards
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                1 User Only
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                 with 1 gun 
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Change on Complain
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                24/7 Support
                            </li>
                            <li>
                                <i class="fa fa-check"></i>
                                Backup Support
                            </li>
                        </ul>
                    </div>
                    <div class="pricing-order">
                        <a href="#" class="bleezy-btn">order now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}
</x-app-layout>
