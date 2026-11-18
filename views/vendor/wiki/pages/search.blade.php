@extends('layouts.app')

@section('title', trans('wiki::messages.search.results'))

@section('content')
    @include('wiki::partials._header', ['title' => trans('wiki::messages.search.results')])

    <div id="wiki" class="kw-results">
        @forelse($pages as $page)
            <a href="{{ route('wiki.pages.show', [$page->category, $page]) }}" class="kw-result">
                <span class="kw-tag"><i class="{{ $page->category->icon ?? 'bi bi-book' }}"></i> {{ $page->category->name }}</span>
                <h2>{{ $page->title }}</h2>
                <p>{{ Str::limit(Str::squish(strip_tags(str_replace('<', ' <', $page->content))), 220) }}</p>
                <span class="kw-result-go">Lire la page <i class="bi bi-arrow-right"></i></span>
            </a>
        @empty
            <div class="ks-empty"><i class="bi bi-search"></i> {{ trans('wiki::messages.search.empty') }}</div>
        @endforelse

        {{ $pages->withQueryString()->links() }}
    </div>
@endsection
