<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carte employe - {{ $employee->firstname }} {{ $employee->name }}</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png"/>
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/icons.css') }}" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
        }

        .print-toolbar {
            max-width: 430px;
            margin: 24px auto 12px;
        }

        .print-stage {
            max-width: 430px;
            margin: 0 auto 24px;
        }

        .employee-profile-export-card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-width: 1px !important;
            border-bottom-width: 3px !important;
            background: #fff;
        }

        @page {
            size: A4;
            margin: 12mm;
        }

        @media print {
            body {
                background: #fff;
            }

            .print-toolbar {
                display: none !important;
            }

            .print-stage {
                max-width: none;
                margin: 0;
            }

            .employee-profile-export-card {
                box-shadow: none;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="print-toolbar d-flex gap-2 justify-content-end">
        <button type="button" class="btn btn-dark" onclick="window.print()">
            <i class="bx bx-printer"></i> Telecharger en PDF
        </button>
        <button type="button" class="btn btn-outline-secondary" onclick="window.close()">
            Fermer
        </button>
    </div>

    <div class="print-stage">
        @include('admin.partials.employee-profile-card', ['employee' => $employee, 'showPrintButton' => false])
    </div>
</body>
</html>