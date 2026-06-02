<footer class="bleezy-footer-area">
    <div class="footer-top-area section_50">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <div class="single-footer-widget footer-logo-widget">
                        <div class="footer-logo">
                            <a href="{{ route('welcome', app()->getLocale()) }}"><img src="{{ asset('images/footer-logo.webp') }}" alt="LOGO" /></a>
                        </div>
                        <div class="footer-widget-text">
                            <p style="text-align: justify">@lang('lang.company_resume').</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="single-footer-widget">
                        <h3>@lang('lang.partnership')</h3>
                        <div class="latest-post-footer clearfix">
                            <div class="latest-post-footer-left">
                                <i class="fa fa-briefcase"></i>
                            </div>
                            <div class="latest-post-footer-right">
                                <h4><a href="{{ route('contacts', app()->getLocale()) }}">@lang('lang.partnership_custom')</a></h4>
                            </div>
                        </div>
                        <div class="latest-post-footer clearfix">
                            <div class="latest-post-footer-left">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="latest-post-footer-right">
                                <h4><a href="{{ route('contacts', app()->getLocale()) }}">@lang('lang.partnership_employee')</a></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="single-footer-widget">
                        <h3>@lang('lang.main_links')</h3>
                        <ul>
                            <li><a href="{{ route('about', app()->getLocale()) }}">@lang('lang.about')</a></li>
                            <li><a href="{{ route('services', app()->getLocale()) }}">@lang('lang.service', array('param'=>"s"))</a></li>
                            <li><a href="{{ route('team', app()->getLocale()) }}">@lang('lang.team')</a></li>
                            <li><a href="{{ route('shops', app()->getLocale()) }}">@lang('lang.shop', array('param'=>"s"))</a></li>
                            <li><a href="{{ route('contacts', app()->getLocale()) }}">@lang('lang.contact', array('param'=>"s"))</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="single-footer-widget">
                        <h3>@lang('lang.newsletter')</h3>
                        <p>@lang('lang.newsletter_describe').</p>
                        <form>
                            <input type="email" placeholder="@lang('lang.email')" >
                            <button title="@lang('lang.submit')"><i class="fa fa-envelope-open-o"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="copyright-right">
                        <p>Copyright &copy; <a href="#">JAGUAR SECURITY SERVICES SARL</a> {{ date('Y') }}. @lang('lang.all_rights')</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>