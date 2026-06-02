<header class="bleezy-header-area">
    <div class="header-right-overlay"></div>
    <div class="mobile-top-menu">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <p>
                        <i class="fa fa-language"></i> 
                        <a href="{{ route(Route::currentRouteName(), 'fr') }}"
                            style="{{ app()->getLocale() == 'fr' ? 'border-bottom: 1px dotted white' : '' }}"
                            title="@lang('lang.switch_in', ['param'=>__('lang.french')])">@lang('lang.french')</a> 
                        | 
                        <a href="{{ route(Route::currentRouteName(), 'en') }}" 
                            style="{{ app()->getLocale() == 'en' ? 'border-bottom: 1px dotted white' : '' }}"
                            title="@lang('lang.switch_in', ['param'=>__('lang.english')])">@lang('lang.english')</a>
                    </p>
                </div>
                <!--<div class="col-md-6 col-sm-6 col-xs-6">-->
                <!--    <div class="cart-top-menu">-->
                <!--        <div class="login dropdown">-->
                <!--            <a href="#" class="dropdown-toggle cart-icon" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">-->
                <!--               <i class="fa fa-shopping-bag"></i> @lang('lang.cart')(02)-->
                <!--            </a>-->
                <!--            <div class="dropdown-menu cart-dropdown" aria-labelledby="dropdownMenu2">-->
                <!--                <h3>Recently added item(s)</h3>-->
                <!--                <ul>-->
                <!--                    <li>-->
                <!--                        <div class="cart-btn-product">-->
                <!--                            <a class="product-remove" href="#"><i class="fa fa-trash-o"></i></a>-->
                <!--                            <div class="cart-btn-pro-img">-->
                <!--                                <a href="#"><img src="{{ asset('images/pro.png') }}" alt="product" /></a>-->
                <!--                            </div>-->
                <!--                            <div class="cart-btn-pro-cont">-->
                <!--                                <h4><a href="#">Wireless IP Camera</a></h4>-->
                <!--                                <span class="item-cat">1x$30.00</span>-->
                <!--                                <span class="price">$30.00</span>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </li>-->
                <!--                    <li>-->
                <!--                        <div class="cart-btn-product">-->
                <!--                            <a class="product-remove" href="#"><i class="fa fa-trash-o"></i></a>-->
                <!--                            <div class="cart-btn-pro-img">-->
                <!--                                <a href="#">-->
                <!--                                    <img src="{{ asset('images/pro-2.png') }}" alt="product" />-->
                <!--                                </a>-->
                <!--                            </div>-->
                <!--                            <div class="cart-btn-pro-cont">-->
                <!--                                <h4><a href="#">Door Lock System</a></h4>-->
                <!--                                <span class="item-cat">1x$130.00</span>-->
                <!--                                <span class="price">$130.00</span>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </li>-->
                <!--                </ul>-->
                <!--                <div class="cart-btn">-->
                <!--                    <a href="#" class="cart-btn-1">View Cart</a>-->
                <!--                    <a href="#" class="cart-btn-2">Checkout</a>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="site-logo">
                    <a href="{{ route('welcome', app()->getLocale()) }}"><img src="{{ asset('images/site-logo.webp') }}" alt="site logo" /></a>
                </div>
            </div>
            <div class="col-md-9">
                <div class="header-right">
                    <div class="header-right-top">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="single-top-right">
                                    <p>@lang('lang.call_us'): <a href="tel:+224625123232" title="@lang('lang.call_us')">+224 625 12 32 32</a></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="single-top-right">
                                    <ul>
                                        <li><a href="https://facebook.com/jsssarl" title="@lang('lang.join_us')"><i class="fa fa-facebook"></i></a></li>
                                        <li><a title="@lang('lang.follow_us')"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="https://www.linkedin.com/company/jaguar-security-services" title="@lang('lang.let_connect')"><i class="fa fa-linkedin"></i></a></li>
                                        <li><a href="mailto:jaguar28jss@gmail.com" title="@lang('lang.mail_us')"><i class="fa fa-envelope"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="single-top-right">
                                    <p>
                                        <i class="fa fa-language"></i> 
                                        <a href="{{ route(Route::currentRouteName(), 'fr') }}" 
                                            style="{{ app()->getLocale() == 'fr' ? 'border-bottom: 1px dotted white' : '' }}"
                                            title="@lang('lang.switch_in', ['param'=>__('lang.french')])">@lang('lang.french')</a> 
                                        | 
                                        <a href="{{ route(Route::currentRouteName(), 'en') }}" 
                                            style="{{ app()->getLocale() == 'en' ? 'border-bottom: 1px dotted white' : '' }}"
                                            title="@lang('lang.switch_in', ['param'=>__('lang.english')])">@lang('lang.english')</a>
                                    </p>
                                    <!--<div class="cart-top-menu">-->
                                    <!--    <div class="login dropdown">-->
                                    <!--        <a href="#" class="dropdown-toggle cart-icon" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">-->
                                    <!--           <i class="fa fa-shopping-bag"></i> @lang('lang.cart')(02)-->
                                    <!--        </a>-->
                                    <!--        <div class="dropdown-menu cart-dropdown" aria-labelledby="dropdownMenu1">-->
                                    <!--            <h3>Recently added item(s)</h3>-->
                                    <!--            <ul>-->
                                    <!--                <li>-->
                                    <!--                    <div class="cart-btn-product">-->
                                    <!--                        <a class="product-remove" href="#"><i class="fa fa-trash-o"></i></a>-->
                                    <!--                        <div class="cart-btn-pro-img">-->
                                    <!--                            <a href="#"><img src="{{ asset('images/pro.png') }}" alt="product" /></a>-->
                                    <!--                        </div>-->
                                    <!--                        <div class="cart-btn-pro-cont">-->
                                    <!--                            <h4><a href="#">Wireless IP Camera</a></h4>-->
                                    <!--                            <span class="item-cat">1x$30.00</span>-->
                                    <!--                            <span class="price">$30.00</span>-->
                                    <!--                        </div>-->
                                    <!--                    </div>-->
                                    <!--                </li>-->
                                    <!--                <li>-->
                                    <!--                    <div class="cart-btn-product">-->
                                    <!--                        <a class="product-remove" href="#">-->
                                    <!--                            <i class="fa fa-trash-o"></i>-->
                                    <!--                        </a>-->
                                    <!--                        <div class="cart-btn-pro-img">-->
                                    <!--                            <a href="#">-->
                                    <!--                                <img src="{{ asset('images/pro-2.png') }}" alt="product" />-->
                                    <!--                            </a>-->
                                    <!--                        </div>-->
                                    <!--                        <div class="cart-btn-pro-cont">-->
                                    <!--                            <h4><a href="#">Door Lock System</a></h4>-->
                                    <!--                            <span class="item-cat">1x$130.00</span>-->
                                    <!--                            <span class="price">$130.00</span>-->
                                    <!--                        </div>-->
                                    <!--                    </div>-->
                                    <!--                </li>-->
                                    <!--            </ul>-->
                                    <!--            <div class="cart-btn">-->
                                    <!--                <a href="#" class="cart-btn-1">View Cart</a>-->
                                    <!--                <a href="#" class="cart-btn-2">Checkout</a>-->
                                    <!--            </div>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="menu-container">
                        <div class="row">
                            <div class="col-md-11 col-sm-11">
                                <!-- Responsive Menu -->
                                <div class="bleezy-responsive-menu"></div>
                                <!-- Responsive Menu -->
                                <div class="mainmenu">
                                    <nav>
                                        <ul id="bleezy_navigation">
                                            <li class="{{ Route::is('welcome', app()->getLocale()) ? 'current-page-item' : '' }}"><a href="{{ route('welcome', app()->getLocale()) }}">@lang('lang.home')</a></li>
                                            <li class="{{ Route::is('about', app()->getLocale()) ? 'current-page-item' : '' }}"><a href="{{ route('about', app()->getLocale()) }}">@lang('lang.about')</a></li>
                                            <li class="{{ Route::is('services', app()->getLocale()) ? 'current-page-item' : '' }}"><a href="{{ route('services', app()->getLocale()) }}">@lang('lang.service', array('param'=>"s"))</a></li>
                                            <li class="{{ Route::is('team', app()->getLocale()) ? 'current-page-item' : '' }}"><a href="{{ route('team', app()->getLocale()) }}">@lang('lang.team', array('param'=>"s"))</a></li>
                                            <li class="{{ Route::is('shops', app()->getLocale()) ? 'current-page-item' : '' }}"><a href="{{ route('shops', app()->getLocale()) }}">@lang('lang.shop', array('param'=>"s"))</a></li>
                                            <li class="{{ Route::is('contacts', app()->getLocale()) ? 'current-page-item' : '' }}"><a href="{{ route('contacts', app()->getLocale()) }}">@lang('lang.contact', array('param'=>"s"))</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-1">
                                <div class="header-search">
                                    <div class="search-icon">
                                        <i class="fa fa-search"></i>
                                    </div>
                                    <div class="search-form">
                                        <form>
                                            <input type="search" placeholder="Search..." >
                                            <button type="submit" ><i class="fa fa-search"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>