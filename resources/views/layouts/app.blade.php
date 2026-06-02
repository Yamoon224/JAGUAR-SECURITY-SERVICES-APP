<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="JAGUAR SECURITY SERVICES OFFICIAL WEBSITE">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/flaticon/flaticon.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/slicknav.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/featherlight.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/featherlight.gallery.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
        @stack('css-view')
    </head>
    <body>
        <x-header-app></x-header-app>

        {{ $slot }}

        <x-footer-app></x-footer-app>
        
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/featherlight.js') }}"></script>
        <script src="{{ asset('assets/js/featherlight.gallery.js') }}"></script>
        <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slicknav.min.js') }}"></script>
        @stack('js-view')
        <script src="{{ asset('assets/js/gmap.js') }}"></script>
        <script src="{{ asset('assets/js/progressbar.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('assets/js/waypoints-min.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>
    </body>
</html>
