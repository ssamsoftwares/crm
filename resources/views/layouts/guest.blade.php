<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Library') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
   <!-- Icons Css -->
   <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
   <!-- App Css-->
   <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
        <!-- Scripts -->
    </head>
    <body class="auth-body-bg">
        <div class="bg-overlay"></div>
        <div class="wrapper-page">
            <div class="container-fluid p-0">
                <div class="card">
                    <div class="card-body">

                        <div class="text-center mt-4">
                            <div class="mb-3">
                            {{-- <h2 style="text-transform:uppercase;color:#555">Library</h2> --}}
                                {{-- <a href="#" class="auth-logo">
                                    <img src="assets/images/logo-dark.png" height="100" width="100" class="logo-dark mx-auto" alt="">
                                    <img src="assets/images/logo-light.png" height="100" width="100" class="logo-light mx-auto" alt="">
                                </a> --}}
                            </div>
                        </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
    </body>
</html>
