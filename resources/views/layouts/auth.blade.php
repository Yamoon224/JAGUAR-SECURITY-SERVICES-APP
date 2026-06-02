<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!--favicon-->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
        <!--plugins-->
        <link href="{{ asset('assets/admin/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
        <!-- loader-->
        <link href="{{ asset('assets/admin/css/pace.min.css') }}" rel="stylesheet" />
        <script src="{{ asset('assets/admin/js/pace.min.js') }}"></script>
        <!-- Bootstrap CSS -->
        <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/admin/css/bootstrap-extended.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link href="{{ asset('assets/admin/css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/admin/css/icons.css') }}" rel="stylesheet">
        @stack('links')
    </head>
    <body class="" style="background-image: url({{ asset('assets/images/bglogin.png') }})">
        <!--wrapper-->
        <div class="wrapper">
            <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
                <div class="container">
                    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-{{ $size }}">
                        <div class="col mx-auto">
                            <div class="card border-dark border-bottom border-top border-3 border-0 mb-0">
                                <div class="card-body">
                                    <div class="p-4">
                                        <div class="mb-3 text-center">
                                            <img src="{{ asset('assets/images/logo-icon.png') }}" width="60" alt="" />
                                        </div>
                                        
                                        {{ $slot }}

                                        <div class="login-separater text-center mb-2"> 
                                            <span>Copyright &copy; JSS {{ date('Y') }}</span>
                                            <hr/>
                                        </div>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end row-->
                </div>
            </div>
        </div>
        <!--end wrapper-->
        <!-- Bootstrap JS -->
        <script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
        <!--plugins-->
        <script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/admin/plugins/simplebar/js/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/admin/plugins/metismenu/js/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/admin/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
        <!--Password show & hide js -->
        <script>
            $(document).ready(function () {
                $("#show_hide_password a").on('click', function (event) {
                    event.preventDefault();
                    if ($('#show_hide_password input').attr("type") == "text") {
                        $('#show_hide_password input').attr('type', 'password');
                        $('#show_hide_password i').addClass("bx-hide");
                        $('#show_hide_password i').removeClass("bx-show");
                    } else if ($('#show_hide_password input').attr("type") == "password") {
                        $('#show_hide_password input').attr('type', 'text');
                        $('#show_hide_password i').removeClass("bx-hide");
                        $('#show_hide_password i').addClass("bx-show");
                    }
                });
            });
        </script>
        <!--app JS-->
        <script src="{{ asset('assets/admin/js/app.js') }}"></script>
    </body>
</html>
