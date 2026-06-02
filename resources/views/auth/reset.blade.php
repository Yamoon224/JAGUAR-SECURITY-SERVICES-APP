<x-auth-layout>
    <div class="text-center mb-4">
        <h5 class="">JSS Admin</h5>
        <p class="mb-0">Welcome to Reset Your Password</p>
    </div>

    <div class="form-body">
        <x-auth-session-status class="mb-2 text-success" :status="session('status')" />
        <form class="row g-3" method="POST" action="{{ route('password.store', app()->getLocale()) }}">
            @csrf
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            
            <div class="my-2">
                <label class="form-label" for="email">@lang('lang.email')</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="example@user.com" value="{{ old('email') }}" required autofocus/>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="my-2">
                <label class="form-label" for="password">@lang('lang.password')</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="my-2">
                <label class="form-label" for="password">@lang('lang.confirm_password')</label>
                <input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="Confirm Password" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            <div class="col-12">
                <div class="d-grid">                
                    <button class="btn btn-dark">@lang('lang.reset')</button>
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
    
