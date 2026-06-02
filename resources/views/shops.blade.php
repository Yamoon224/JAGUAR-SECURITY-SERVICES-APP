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
                            <li>@lang('lang.shop', ['param'=>"s"])</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcromb Area End -->

<section class="bleezy-shop-page-area section_t_70 section_b_70">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="shop-left-sidebar">
                    <div class="shop-sidebar-widget">
                        <form>
                            <input type="search" placeholder="Search..." >
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                     <div class="shop-sidebar-widget widget_product_categories">
                        <h3>by Categories</h3>
                        <ul class="product-categories">
                            <li class="cat-parent current-cat-parent">
                                <a href="#">home security <span><i class="fa fa-angle-down"></i></span></a>
                                <ul class="children">
                                    <li class="current-cat"><a href="#">CCTV Camera</a></li>
                                    <li><a href="#">Fingerprint lock </a></li>
                                    <li><a href="#">Digital door sytem</a></li>
                                </ul>
                            </li>
                            <li class="cat-parent">
                                <a href="#">Outdoor security <span><i class="fa fa-angle-down"></i></span></a>
                                <ul class="children">
                                    <li><a href="#">CCTV Camera</a></li>
                                    <li><a href="#">Fingerprint lock</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Cloud Security</a></li>
                            <li><a href="#">Watches</a></li>
                            <li><a href="#">computer Secutity</a></li>
                            <li><a href="#">Guard</a></li>
                            <li><a href="#">Gun Security</a></li>
                        </ul>
                    </div>
                    <div class="shop-sidebar-widget">
                        <h3>related products</h3>
                        <ul class="featured-list">
                            <li class="sidebr-pro-widget">
                                <div class="product-thumb-info">
                                    <div class="product-thumb-info-image">
                                        <a href="#">
                                            <img src="{{ asset('images/pro-2.png') }}" alt="proudct" />
                                        </a>
                                    </div>
                                    <div class="product-thumb-info-content">
                                        <h4><a href="#">door lock system</a></h4>
                                        <span class="item-cat">
                                            <a href="#">
                                                Stock clearance
                                            </a>
                                        </span>
                                        <span class="price">
                                           $180
                                        </span>
                                    </div>
                                </div>
                            </li>
                            <li class="sidebr-pro-widget">
                                <div class="product-thumb-info">
                                    <div class="product-thumb-info-image">
                                        <a href="#">
                                            <img src="{{ asset('images/pro.png') }}" alt="proudct" />
                                        </a>
                                    </div>
                                    <div class="product-thumb-info-content">
                                        <h4><a href="#">IP CCTV Camera</a></h4>
                                        <span class="item-cat">
                                            <a href="#">
                                                Stock clearance
                                            </a>
                                        </span>
                                        <span class="price">
                                            $290
                                        </span>
                                    </div>
                                </div>
                            </li>
                            <li class="sidebr-pro-widget">
                                <div class="product-thumb-info">
                                    <div class="product-thumb-info-image">
                                        <a href="#">
                                            <img src="{{ asset('images/pro-2.png') }}" alt="proudct" />
                                        </a>
                                    </div>
                                    <div class="product-thumb-info-content">
                                        <h4><a href="#">door lock system</a></h4>
                                        <span class="item-cat">
                                            <a href="#">
                                                Stock clearance
                                            </a>
                                        </span>
                                        <span class="price">
                                            $180
                                        </span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="bleezy-shop-left margin-top">
                    <div class="shorting">
                        <div class="row">
                            <div class="col-sm-6">
                                <p>Showing 1–6 of 7 results</p>
                            </div>
                            <div class="col-sm-6">
                                <form method="post">
                                    <label>
                                        <select>
                                            <option value="0" selected="selected">Default Shorting</option>
                                            <option value="1">Sort by popularity</option>
                                            <option value="2">Sort by average rating</option>
                                            <option value="3">Sort by newness</option>
                                            <option value="4">Sort by price: low to high</option>
                                        </select>
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <div class="single-shop-product">
                                <div class="single-product-image">
                                    <a href="#">
                                        <img src="{{ asset('images/product-1.jpg') }}" alt="product" />
                                    </a>
                                </div>
                                <div class="single-product-text">
                                    <h3>
                                        <a href="#">Wireless IP Camera</a>
                                    </h3>
                                    <div class="product-price">
                                        <h3>$190</h3>
                                        <ul class="product-rating">
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star-half-o"></i></li>
                                        </ul>
                                    </div>
                                    <div class="product-button">
                                        <a href="#">add to cart</a>
                                        <a href="#"><i class="fa fa-heart"></i></a>
                                        <a href="#"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="single-shop-product">
                                <div class="single-product-image">
                                    <a href="#">
                                        <img src="{{ asset('images/product-2.jpg') }}" alt="product" />
                                    </a>
                                </div>
                                <div class="single-product-text">
                                    <h3>
                                        <a href="#">Indoor Camera</a>
                                    </h3>
                                    <div class="product-price">
                                        <h3>$230</h3>
                                        <ul class="product-rating">
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star-half-o"></i></li>
                                        </ul>
                                    </div>
                                    <div class="product-button">
                                        <a href="#">add to cart</a>
                                        <a href="#"><i class="fa fa-heart"></i></a>
                                        <a href="#"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="single-shop-product">
                                <div class="single-product-image">
                                    <a href="#">
                                        <img src="{{ asset('images/product-3.jpg') }}" alt="product" />
                                    </a>
                                </div>
                                <div class="single-product-text">
                                    <h3>
                                        <a href="#">Door Lock System</a>
                                    </h3>
                                    <div class="product-price">
                                        <h3>$230</h3>
                                        <ul class="product-rating">
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star-half-o"></i></li>
                                        </ul>
                                    </div>
                                    <div class="product-button">
                                        <a href="#">add to cart</a>
                                        <a href="#"><i class="fa fa-heart"></i></a>
                                        <a href="#"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <div class="single-shop-product">
                                <div class="single-product-image">
                                    <a href="#">
                                        <img src="{{ asset('images/product-4.jpg') }}" alt="product" />
                                    </a>
                                </div>
                                <div class="single-product-text">
                                    <h3>
                                        <a href="#">RFID card access</a>
                                    </h3>
                                    <div class="product-price">
                                        <h3>$310</h3>
                                        <ul class="product-rating">
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star-half-o"></i></li>
                                        </ul>
                                    </div>
                                    <div class="product-button">
                                        <a href="#">add to cart</a>
                                        <a href="#"><i class="fa fa-heart"></i></a>
                                        <a href="#"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="single-shop-product">
                                <div class="single-product-image">
                                    <a href="#">
                                        <img src="{{ asset('images/product-5.jpg') }}" alt="product" />
                                    </a>
                                </div>
                                <div class="single-product-text">
                                    <h3>
                                        <a href="#">Pin protected lock </a>
                                    </h3>
                                    <div class="product-price">
                                        <h3>$210</h3>
                                        <ul class="product-rating">
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star-half-o"></i></li>
                                        </ul>
                                    </div>
                                    <div class="product-button">
                                        <a href="#">add to cart</a>
                                        <a href="#"><i class="fa fa-heart"></i></a>
                                        <a href="#"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="single-shop-product">
                                <div class="single-product-image">
                                    <a href="#">
                                        <img src="{{ asset('images/product-6.jpg') }}" alt="product" />
                                    </a>
                                </div>
                                <div class="single-product-text">
                                    <h3>
                                        <a href="#">fire alarm</a>
                                    </h3>
                                    <div class="product-price">
                                        <h3>$130</h3>
                                        <ul class="product-rating">
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star-half-o"></i></li>
                                        </ul>
                                    </div>
                                    <div class="product-button">
                                        <a href="#">add to cart</a>
                                        <a href="#"><i class="fa fa-heart"></i></a>
                                        <a href="#"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <div class="single-shop-product">
                                <div class="single-product-image">
                                    <a href="#">
                                        <img src="{{ asset('images/product-7.jpg') }}" alt="product" />
                                    </a>
                                </div>
                                <div class="single-product-text">
                                    <h3>
                                        <a href="#">Door Lock System</a>
                                    </h3>
                                    <div class="product-price">
                                        <h3>$230</h3>
                                        <ul class="product-rating">
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star-half-o"></i></li>
                                        </ul>
                                    </div>
                                    <div class="product-button">
                                        <a href="#">add to cart</a>
                                        <a href="#"><i class="fa fa-heart"></i></a>
                                        <a href="#"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="single-shop-product">
                                <div class="single-product-image">
                                    <a href="#">
                                        <img src="{{ asset('images/product-4.jpg') }}" alt="product" />
                                    </a>
                                </div>
                                <div class="single-product-text">
                                    <h3>
                                        <a href="#">RFID card access</a>
                                    </h3>
                                    <div class="product-price">
                                        <h3>$310</h3>
                                        <ul class="product-rating">
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star-half-o"></i></li>
                                        </ul>
                                    </div>
                                    <div class="product-button">
                                        <a href="#">add to cart</a>
                                        <a href="#"><i class="fa fa-heart"></i></a>
                                        <a href="#"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="single-shop-product">
                                <div class="single-product-image">
                                    <a href="#">
                                        <img src="{{ asset('images/product-1.jpg') }}" alt="product" />
                                    </a>
                                </div>
                                <div class="single-product-text">
                                    <h3>
                                        <a href="#">Wireless IP Camera</a>
                                    </h3>
                                    <div class="product-price">
                                        <h3>$190</h3>
                                        <ul class="product-rating">
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star-half-o"></i></li>
                                        </ul>
                                    </div>
                                    <div class="product-button">
                                        <a href="#">add to cart</a>
                                        <a href="#"><i class="fa fa-heart"></i></a>
                                        <a href="#"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pagination-box">
                            <ul class="pagination">
                                <li><a href="#"><i class="fa fa-angle-double-left"></i></a></li>
                                <li><a href="#">1</a></li>
                                <li class="active"><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li><a href="#"><i class="fa fa-angle-double-right"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</x-app-layout>
