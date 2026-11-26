@extends('layouts.app')

@push('styles')
    <link href="{{ plugin_asset('koriaforum', 'css/forum.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="kf">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('forum')
    </div>
@endsection
