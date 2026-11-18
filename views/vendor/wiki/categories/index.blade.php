@extends('layouts.app')

@section('title', trans('wiki::messages.title'))

@php
    $koriaPageCount = $categories->sum(fn ($category) => $category->pages->count()
        + $category->categories->sum(fn ($sub) => $sub->pages->count()));
@endphp

@section('content')
    <section class="kw-hero ks-wood">
        <span class="kw-hero-badge" aria-hidden="true">
            <span class="k-hex">
                <svg viewBox="0 0 120 128">
                    <polygon class="k-hex-frame" points="60,4 112,33 112,95 60,124 8,95 8,33"/>
                    <polygon class="k-hex-inner" points="60,18 99,40 99,88 60,110 21,88 21,40"/>
                    <path class="k-hex-shine" d="M21 40 L60 18 L99 40 L99 56 Q60 44 21 56 Z"/>
                </svg>
                <i class="bi bi-book-half"></i>
            </span>
        </span>

        <div class="kw-hero-body">
            <span class="k-sign"><i class="bi bi-compass-fill"></i> Le guide de l'île</span>
            <h1 class="kw-hero-title">{{ trans('wiki::messages.title') }}</h1>
            <p class="kw-hero-text">Tout ce qu'il faut savoir pour bien démarrer et devenir le roi de l'île sur {{ site_name() }}.</p>

            @include('wiki::partials._search', ['searchClass' => 'kw-search-lg', 'searchPlaceholder' => 'Rechercher : minerais, grades, kits…'])

            <div class="kw-hero-stats">
                <span><i class="bi bi-folder2-open"></i> {{ $categories->count() }} {{ $categories->count() > 1 ? 'catégories' : 'catégorie' }}</span>
                <span><i class="bi bi-file-earmark-text"></i> {{ $koriaPageCount }} {{ $koriaPageCount > 1 ? 'pages' : 'page' }}</span>
            </div>
        </div>
    </section>

    <div class="kw-grid" id="wiki">
        @forelse($categories as $category)
            @php
                $koriaPages = $category->pages;
                $koriaTotal = $koriaPages->count() + $category->categories->sum(fn ($sub) => $sub->pages->count());
            @endphp

            <article class="kw-cat kw-c{{ $loop->index % 5 }}">
                <a href="{{ route('wiki.show', $category) }}" class="kw-cat-top">
                    <span class="kw-cat-icon"><i class="{{ $category->icon ?? 'bi bi-book' }}"></i></span>
                    <h2>{{ $category->name }}</h2>
                </a>

                <div class="kw-cat-body">
                    <span class="kw-cat-count">
                        @if($koriaTotal > 0)
                            {{ $koriaTotal }} {{ $koriaTotal > 1 ? 'pages' : 'page' }}
                        @else
                            Bientôt
                        @endif
                    </span>

                    @if($koriaPages->isEmpty() && $category->categories->isEmpty())
                        <p class="kw-cat-soon"><i class="bi bi-hourglass-split"></i> Bientôt des pages ici !</p>
                    @else
                        <ul class="kw-cat-pages">
                            @foreach($koriaPages->take(4) as $catPage)
                                <li>
                                    <a href="{{ route('wiki.pages.show', [$category, $catPage]) }}">
                                        <i class="bi bi-caret-right-fill"></i> {{ $catPage->title }}
                                    </a>
                                </li>
                            @endforeach
                            @foreach($category->categories->take(max(0, 4 - $koriaPages->count())) as $subCategory)
                                <li>
                                    <a href="{{ route('wiki.show', $subCategory) }}">
                                        <i class="{{ $subCategory->icon ?? 'bi bi-folder2' }}"></i> {{ $subCategory->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ route('wiki.show', $category) }}" class="kw-cat-more">
                            Explorer <i class="bi bi-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </article>
        @empty
            <div class="ks-empty"><i class="bi bi-book"></i> Le wiki arrive bientôt !</div>
        @endforelse
    </div>
@endsection
