{{-- Habillage KoriaMC de l'admin. Le reste du fichier est identique à l'original d'Azuriom 1.2. --}}
@php($k = app()->bound('koria.theme') ? app('koria.theme') : app()->instance('koria.theme', require resource_path('themes/koria/config/helpers.php')))
@if($k->on('admin_skin'))
    <link href="{{ $k->fontUrl() }}" rel="stylesheet">
    <link href="{{ theme_asset('css/admin.css') }}&t={{ @filemtime(resource_path('themes/koria/assets/css/admin.css')) }}" rel="stylesheet">
    <style>{!! $k->cssVars() !!}</style>
    @if(! setting('icon'))
        <script>document.querySelectorAll('link[rel="shortcut icon"]').forEach(function (l) { l.href = @json(theme_asset('img/favicon.png')); });</script>
    @endif
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" aria-label="Close"></button>
    </div>
@endif

<div id="status-message"></div>
