<!DOCTYPE html>
@include('elements.base')
@php
    $k = app()->bound('koria.theme') ? app('koria.theme') : app()->instance('koria.theme', require resource_path('themes/koria/config/helpers.php'));
    $koriaLogo = $k->get('hero_logo') ? image_url($k->get('hero_logo')) : (setting('logo') ? image_url(setting('logo')) : theme_asset('img/logo.webp'));
    $koriaIcon = setting('icon') ? favicon() : theme_asset('img/favicon.png');
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', setting('description', ''))">
    <meta name="theme-color" content="{{ $k->color('color_leaf') }}">
    <meta name="author" content="{{ site_name() }}">

    <meta property="og:title" content="@yield('title')">
    <meta property="og:type" content="@yield('type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $koriaLogo }}">
    <meta property="og:description" content="@yield('description', setting('description', ''))">
    <meta property="og:site_name" content="{{ site_name() }}">
    @stack('meta')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ site_name() }}</title>

    <link rel="shortcut icon" href="{{ $koriaIcon }}">

    <script>document.documentElement.classList.replace('no-js', 'js');</script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('vendor/axios/axios.min.js') }}" defer></script>
    <script src="{{ asset('js/script.js') }}" defer></script>
    <script src="{{ theme_asset('js/theme.js') }}&t={{ @filemtime(resource_path('themes/koria/assets/js/theme.js')) }}" defer></script>
    @stack('scripts')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ $k->fontUrl() }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    @stack('styles')
    <link href="{{ theme_asset('css/style.css') }}&t={{ @filemtime(resource_path('themes/koria/assets/css/style.css')) }}" rel="stylesheet">
    <style>{!! $k->cssVars() !!}</style>
</head>

<body class="koria @yield('body-class') @unless($k->on('animations')) k-no-anim @endunless"
      data-money-name="{{ money_name() }}"
      data-money-icon="{{ theme_asset('img/coin.png') }}">
<div id="app" class="flex-shrink-0">
    <header>
        @include('elements.navbar')
    </header>

    @yield('app')
</div>

@include('elements.footer')

<div class="k-toast" id="koriaToast" role="status" aria-live="polite"></div>

@stack('footer-scripts')

</body>
</html>
