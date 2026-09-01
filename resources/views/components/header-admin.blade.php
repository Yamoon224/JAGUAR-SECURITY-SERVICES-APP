<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand gap-3">
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>

            <div class="position-relative search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                <input class="form-control px-5" disabled type="search" placeholder="@lang('lang.search')...">
                <span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-5"><i class='bx bx-search'></i></span>
            </div>


            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-1">
                    <li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                        <a class="nav-link" href="avascript:;"><i class='bx bx-search'></i></a>
                    </li>
                    <li class="nav-item dropdown dropdown-laungauge d-none d-sm-flex">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="avascript:;" data-bs-toggle="dropdown">
                            <img src="{{ asset('images/countries/'.app()->getLocale().'.webp') }}" width="22" alt="">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('lang', (app()->getLocale() == 'fr' ? 'en' : 'fr')) }}">
                                    <img src="{{ asset('images/countries/'.(app()->getLocale() == 'fr' ? 'en' : 'fr').'.webp') }}" width="20" alt="">
                                    <span class="ms-2">@lang('lang.'.(app()->getLocale() == 'fr' ? 'english' : 'french'))</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dark-mode d-none d-sm-flex">
                        <a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
                        </a>
                    </li>

                    <li class="nav-item dropdown dropdown-app">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="javascript:;"><i class='bx bx-grid-alt'></i></a>
                        <div class="dropdown-menu dropdown-menu-end p-0">
                            <div class="app-container p-2 my-2">
                                <div class="row gx-0 gy-2 row-cols-3 justify-content-center p-2">
                                    <div class="col">
                                        <a href="{{ getDashboardRoute() }}">
                                            <div class="app-box text-center">
                                                <div class="app-icon"><i class="bx bx-tachometer bx-sm"></i></div>
                                                <div class="app-name"><p class="mb-0 mt-1">@lang('lang.dashboard')</p></div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="{{ route('employees.index') }}">
                                            <div class="app-box text-center">
                                                <div class="app-icon"><i class="bx bx-group bx-sm"></i></div>
                                                <div class="app-name"><p class="mb-0 mt-1">@lang('lang.employee', ['param'=>'s'])</p></div>
                                            </div>
                                        </a>
                                    </div>  
                                    
                                    @if(isRightAccess([1, 2]))
                                    <div class="col">
                                        <a href="{{ route('customers.index') }}">
                                            <div class="app-box text-center">
                                                <div class="app-icon"><i class="bx bx-buildings bx-sm"></i></div>
                                                <div class="app-name"><p class="mb-0 mt-1">@lang('lang.customer', ['param'=>'s'])</p></div>
                                            </div>
                                        </a>
                                    </div>   
                                    @endif
                                    
                                    @if(isRightAccess([1, 5]))
                                    <div class="col">
                                        <a href="{{ route('equipments.index') }}">
                                            <div class="app-box text-center">
                                                <div class="app-icon"><i class="bx bx-tachometer bx-sm"></i></div>
                                                <div class="app-name"><p class="mb-0 mt-1">@lang('lang.logistic', ['param'=>'s'])</p></div>
                                            </div>
                                        </a>
                                    </div>
                                    @endif
                                    
                                    @if(isRightAccess([1, 4]))
                                    <div class="col">
                                        <a href="{{ route('meets.index') }}">
                                            <div class="app-box text-center">
                                                <div class="app-icon"><i class="bx bx-calendar bx-sm"></i></div>
                                                <div class="app-name"><p class="mb-0 mt-1">@lang('lang.meet', ['param'=>'s'])</p></div>
                                            </div>
                                        </a>
                                    </div>  
                                    <div class="col">
                                        <a href="{{ route('mails.index') }}">
                                            <div class="app-box text-center">
                                                <div class="app-icon"><i class="bx bx-envelope bx-sm"></i></div>
                                                <div class="app-name"><p class="mb-0 mt-1">@lang('lang.mails', ['param'=>'s'])</p></div>
                                            </div>
                                        </a>
                                    </div>   
                                    @endif
                                    
                                    @if(isRightAccess([1, 2]))
                                    <div class="col">
                                        <a href="{{ route('bills.index') }}">
                                            <div class="app-box text-center">
                                                <div class="app-icon"><i class="bx bx-wallet-alt bx-sm"></i></div>
                                                <div class="app-name"><p class="mb-0 mt-1">@lang('lang.bill', ['param'=>'s'])</p></div>
                                            </div>
                                        </a>
                                    </div>   
                                    @endif
                                    
                                    @if(isRightAccess([1]))
                                    <div class="col">
                                        <a href="{{ route('groups', app()->getLocale()) }}">
                                            <div class="app-box text-center">
                                                <div class="app-icon"><i class="bx bx-table bx-sm"></i></div>
                                                <div class="app-name"><p class="mb-0 mt-1">@lang('lang.group', ['param'=>'s'])</p></div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="{{ route('users.index') }}">
                                            <div class="app-box text-center">
                                                <div class="app-icon"><i class="bx bx-group bx-sm"></i></div>
                                                <div class="app-name"><p class="mb-0 mt-1">@lang('lang.user', ['param'=>'s'])</p></div>
                                            </div>
                                        </a>
                                    </div>  
                                    @endif
                                    
                                    @if(isRightAccess([1, 5]))
                                    <div class="col">
                                        <a href="{{ route('equipments.index') }}">
                                            <div class="app-box text-center">
                                                <div class="app-icon"><i class="bx bx-customize bx-sm"></i></div>
                                                <div class="app-name"><p class="mb-0 mt-1">@lang('lang.equipment', ['param'=>'s'])</p></div>
                                            </div>
                                        </a>
                                    </div>
                                    @endif
                                </div>        
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown dropdown-large">
                        <!--<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown">-->
                        <!--    <i class='bx bx-bell'></i>-->
                        <!--</a>-->
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:;">
                                <div class="msg-header">
                                    <p class="msg-header-title">Notifications</p>
                                    <p class="msg-header-badge">8 New</p>
                                </div>
                            </a>
                            <div class="header-notifications-list">
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="user-online">
                                            <img src="{{ asset('images/profile.png') }}" class="msg-avatar" alt="user avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">Daisy Anderson<span class="msg-time float-end">5 sec
                                        ago</span></h6>
                                            <p class="msg-info">The standard chunk of lorem</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <a href="javascript:;">
                                <div class="text-center msg-footer">
                                    <button class="btn btn-primary w-100">View All Notifications</button>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-large">
                        <!--<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">-->
                        <!--    <i class='bx bx-shopping-bag'></i>-->
                        <!--</a>-->
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:;">
                                <div class="msg-header">
                                    <p class="msg-header-title">My Cart</p>
                                    <p class="msg-header-badge">10 Items</p>
                                </div>
                            </a>
                            <div class="header-message-list">
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="position-relative">
                                            <div class="cart-product rounded-circle bg-light">
                                                <img src="{{ asset('images/products/11.png') }}" class="" alt="product image">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="cart-product-title mb-0">Men White T-Shirt</h6>
                                            <p class="cart-product-price mb-0">1 X $29.00</p>
                                        </div>
                                        <div class="">
                                            <p class="cart-price mb-0">$250</p>
                                        </div>
                                        <div class="cart-product-cancel"><i class="bx bx-x"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <a href="javascript:;">
                                <div class="text-center msg-footer">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h5 class="mb-0">Total</h5>
                                        <h5 class="mb-0 ms-auto">$489.00</h5>
                                    </div>
                                    <button class="btn btn-primary w-100">Checkout</button>
                                </div>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="user-box dropdown px-3">
                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset(auth()->user()->photo ?? 'images/profile.png') }}" class="user-img" alt="user avatar">
                    <div class="user-info">
                        <p class="user-name mb-0">{{ ucfirst(auth()->user()->name) }}</p>
                        <p class="designattion mb-0">{{ ucfirst(auth()->user()->group->name) }}</p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <!--<li><a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}"><i class="bx bx-user fs-5"></i><span>@lang('lang.profil')</span></a></li>-->
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ getDashboardRoute() }}"><i class="bx bx-home-circle fs-5"></i><span>@lang('lang.dashboard')</span></a>
                    </li>
                    <li><div class="dropdown-divider mb-0"></div></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"><i class="bx bx-log-out-circle"></i><span>@lang('lang.logout')</span></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>