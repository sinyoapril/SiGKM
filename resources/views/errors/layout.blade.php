<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="light-style"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{ asset('template/assets') }}/"
    data-template="vertical-menu-template-free"
>
    <head>
        <meta charset="utf-8">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        >
        <meta name="description" content="Sistem Informasi Repository Gugus Kendali Mutu">

        <title>@yield('title', 'Terjadi Kesalahan') - {{ config('app.name', 'SiGKM') }}</title>

        <link rel="icon" type="image/x-icon" href="{{ asset('template/assets/img/favicon/favicon.ico') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
            rel="stylesheet"
        >

        <link rel="stylesheet" href="{{ asset('template/assets/vendor/fonts/boxicons.css') }}">
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/core.css') }}" class="template-customizer-core-css">
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css">
        <link rel="stylesheet" href="{{ asset('template/assets/css/demo.css') }}">
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/pages/page-misc.css') }}">

        <script src="{{ asset('template/assets/vendor/js/helpers.js') }}"></script>
        <script src="{{ asset('template/assets/js/config.js') }}"></script>
    </head>

    <body>
        <div class="container-xxl container-p-y">
            <div class="misc-wrapper">
                <h1 class="mb-2 mx-2">@yield('code')</h1>
                <h2 class="mb-2 mx-2">@yield('title')</h2>
                <p class="mb-4 mx-2">@yield('message')</p>

                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn btn-primary">
                    Kembali ke Beranda
                </a>

                <div class="mt-3">
                    <img
                        src="{{ asset('template/assets/img/illustrations/page-misc-error-light.png') }}"
                        alt="@yield('title')"
                        width="500"
                        class="img-fluid"
                        data-app-dark-img="illustrations/page-misc-error-light.png"
                        data-app-light-img="illustrations/page-misc-error-light.png"
                    >
                </div>
            </div>
        </div>

        <script src="{{ asset('template/assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/js/menu.js') }}"></script>
        <script src="{{ asset('template/assets/js/main.js') }}"></script>
    </body>
</html>
