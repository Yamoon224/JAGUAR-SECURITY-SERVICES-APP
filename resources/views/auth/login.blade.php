<x-auth-layout :size="3">
    <div class="text-center mb-4">
        <h6 class="">JAGUAR SECURITY SERVICES SARL</h6>
        @if(!$errors->isEmpty())
        <p class="mb-0 text-danger">{{ $errors->get('email')[0] }}</p>
        @endif
    </div>

    <div class="form-body">
        <form class="row g-3" method="POST" action="{{ route('auth', app()->getLocale()) }}">
            @csrf
            <x-auth-session-status class="mb-2 text-danger" :status="session('status')" />
            <div class="col-12">
                <input type="email" class="form-control" id="inputEmailAddress" name="email" placeholder="@lang('lang.email')" required>
            </div>
            <div class="col-12">
                <div class="input-group" id="show_hide_password">
                    <input type="password" class="form-control border-end-0" id="inputChoosePassword" name="password" placeholder="@lang('lang.password')" required> 
                    <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                </div>
            </div>
            <div class="col-12">
                <div class="form-check form-switch form-check-dark">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDark" name="remember">
                    <label class="form-check-label" for="flexSwitchCheckDark">@lang('lang.remember_me')</label>
                </div>
            </div>
            <div class="col-12">
                <div class="d-grid">
                    <button class="btn btn-dark">@lang('lang.auth')</button>
                </div>
            </div>
            @if (Route::has('password.request'))
            <div class="col-12">
                <div class="text-center">
                    <p class="mb-0">@lang('lang.forgot') ? <a href="{{ route('password.request', app()->getLocale()) }}" class="text-dark"><b>@lang('lang.reset_here')</b></a></p>
                </div>
            </div>
            @endif
        </form>
    </div>
</x-auth-layout>

