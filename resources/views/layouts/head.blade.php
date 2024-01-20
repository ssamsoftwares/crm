<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />

    @stack('page-title')

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ url('assets/images/favicon.ico') }}">

    <!-- DataTables -->
    <link href="{{ url('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ url('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />

    <!-- twitter-bootstrap-wizard css -->
    <link rel="stylesheet" href="assets/libs/twitter-bootstrap-wizard/prettify.css">

    {{--  Print css  --}}
    <link href="{{ url('assets/css/app-print.css') }}" id="app-print-style" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ url('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ url('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ url('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Plugins css -->
    <link href="{{ url('assets/libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

     <!-- Select 2 css -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .alert p {
            margin-bottom: 0;
        }

        table.dataTable>tbody>tr.child ul.dtr-details>li {
            white-space: pre-wrap;
        }

        .dataTables_filter {
            display: none;
        }


        /* Button Css */
        .ri-eye-line:before {
            content: "\ec95";
            position: absolute;
            left: 13px;
            top: 5px;
        }

        a.btn.btn-primary.waves-effect.waves-light.view {
            width: 41px;
            height: 32px;
        }

        .action-btns.text-center {
            display: flex;
            gap: 10px;
        }

        .ri-pencil-line:before {
            content: "\ef8c";
            position: absolute;
            left: 13px;
            top: 5px;
        }

        a.btn.btn-info.waves-effect.waves-light.edit {
            width: 41px;
            height: 32px;
        }

        table.dataTable>tbody>tr.child ul.dtr-details>li {
            white-space: nowrap !important;
        }
        /* responsive filter in table css */





    </style>
</head>
@stack('style')
