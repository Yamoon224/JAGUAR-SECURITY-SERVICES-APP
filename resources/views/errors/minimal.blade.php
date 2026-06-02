<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!--favicon-->
        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png" />
        <!-- loader-->
        <link href="{{ asset('admin/css/pace.min.css') }}" rel="stylesheet" />
        <script src="{{ asset('admin/js/pace.min.js') }}"></script>
        <!-- Bootstrap CSS -->
        <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/css/bootstrap-extended.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link href="{{ asset('admin/css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/css/icons.css') }}" rel="stylesheet">
    </head>
    <body>
        <!-- wrapper -->
        <div class="wrapper">
            <div class="error-@yield('code') d-flex align-items-center justify-content-center">
                <div class="container">
                    <div class="card py-5">
                        <div class="row g-0">
                            <div class="col col-xl-5">
                                <div class="card-body p-4">
                                    <h1 class="display-1">@yield('code')</h1>
                                    <h2 class="font-weight-bold display-4">@yield('title')</h2>
                                    <p>@yield('message').</p>
                                    <div class="mt-5">
                                        <a onclick="history.back()" class="btn btn-dark btn-lg ms-3 px-md-5 radius-30">Go Back</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-7">
                                <img src="https://cdn.searchenginejournal.com/wp-content/uploads/2019/03/shutterstock_1338315902.png" class="img-fluid" alt="">
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
