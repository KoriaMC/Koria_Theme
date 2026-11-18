@extends('layouts.app')

@section('title', trans('wiki::messages.title'))

@section('content')
    @include('wiki::partials._header', [
        'title' => trans('wiki::messages.title'),
        'subtitle' => 'Toutes les infos pour bien démarrer et maîtriser KoriaMC.',
    ])

    <div class="k-wiki-grid" id="wiki">
        @forelse($categories as $category)
            <a href="{{ route('wiki.show', $category) }}" class="k-box k-wiki-cat mb-0">
                <span class="k-hex" aria-hidden="true">
                    <svg viewBox="0 0 120 128">
                        <polygon class="k-hex-frame" points="60,4 112,33 112,95 60,124 8,95 8,33"/>
                        <polygon class="k-hex-inner" points="60,18 99,40 99,88 60,110 21,88 21,40"/>
                        <path class="k-hex-shine" d="M21 40 L60 18 L99 40 L99 56 Q60 44 21 56 Z"/>
                    </svg>
                    <i class="{{ $category->icon ?? 'bi bi-book' }}"></i>
                </span>
                <h2>{{ $category->name }}</h2>
                <span>Lire <i class="bi bi-arrow-right"></i></span>
            </a>
        @empty
            <div class="k-box"><p class="k-empty"><i class="bi bi-book"></i> Le wiki arrive bientôt !</p></div>
        @endforelse
    </div>
@endsection
