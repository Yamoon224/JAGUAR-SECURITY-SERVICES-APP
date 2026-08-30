<div class="card border-dark border-bottom border-top border-3 border-0 mb-0 w-100">
    <div class="card-body">
        <div class="p-4">
            <div class="mb-3 text-center">
                <img src="{{ asset('images/logo-icon.png') }}" width="60" alt="JSS" />
            </div>

            {{ $slot }}

            <div class="login-separater text-center mb-2">
                <span>Copyright &copy; JSS {{ date('Y') }}</span>
                <hr/>
            </div>
        </div>
    </div>
</div>
