<x-auth-layout :size="3">
    <div class="text-center mb-4">
        <h5 class="">JSS Admin</h5>
        <p class="mb-0">Enter your registered email ID to reset the password</p>
    </div>

    <div class="form-body">
        <x-auth-session-status class="mb-4 text-success" :status="session('status')" />
        <form class="row g-3" method="POST" action="{{ route('password.email', app()->getLocale()) }}">
            @csrf
            <div class="my-4">
                <label class="form-label" for="email">@lang('Email')</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="jaguar28jss@gmail.com" value="{{ old('email') }}" required autofocus/>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="col-12">
                <div class="d-grid">                
                    <button class="btn btn-dark">@lang('Email Password Reset Link')</button>
                </div>
            </div>
            <div class="col-12">
                <div class="text-center">
                    <p class="mb-0">@lang('Do You Have Account') ? <a href="{{ route('login', app()->getLocale()) }}" class="text-dark"><b>@lang('lang.sign_in')</b></a></p>
                </div>
            </div>
        </form>
    </div>
</x-auth-layout>
    
