@extends('layouts.base')

@section('app')
    <main class="container content k-page-content">
        @include('elements.session-alerts')

        @yield('content')
    </main>
@endsection
