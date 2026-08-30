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

        @if(!empty($cover))
        <style>
            .auth-split { min-height: 100vh; }
            .auth-split__cover {
                position: relative;
                background: #111 url('{{ $cover }}') center / cover no-repeat;
            }
            .auth-split__cover::after {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(0,0,0,.05), rgba(0,0,0,.45));
            }
            /* display:flex + margin:auto sur l'enfant : centré verticalement quand
               il y a de la place, et intégralement défilable (haut ET bas) quand
               le contenu dépasse la hauteur de l'écran. */
            .auth-split__form {
                display: flex;
                min-height: 100vh;
                padding: 2.5rem 1rem;
                background: #f7f7f9;
            }
            .auth-split__form .card-wrap {
                width: 100%;
                max-width: {{ $wide ? '660px' : '460px' }};
                margin: auto;
            }
            @media (min-width: 992px) {
                .auth-split__cover {
                    position: sticky;
                    top: 0;
                    align-self: flex-start;
                    height: 100vh;
                }
            }
        </style>
        @endif
    </head>
    <body @if(empty($cover)) style="background-image: url({{ asset('images/bglogin.png') }})" @endif>
        <!--wrapper-->
        <div class="wrapper">
            @if(!empty($cover))
            {{-- Mise en page en deux colonnes : image / formulaire --}}
            <div class="row g-0 auth-split">
                <div class="col-lg-7 d-none d-lg-block auth-split__cover"></div>
                <div class="col-lg-5 auth-split__form">
                    <div class="card-wrap">
                        @include('layouts.partials.auth-card')
                    </div>
                </div>
            </div>
            @else
            <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
                <div class="container">
                    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-{{ $size }}">
                        <div class="col mx-auto">
                            @include('layouts.partials.auth-card')
                        </div>
                    </div>
                </div>
            </div>
            @endif
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
