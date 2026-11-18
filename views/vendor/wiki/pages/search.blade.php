@extends('layouts.app')

@section('title', trans('wiki::messages.search.results'))

@section('content')
    @include('wiki::partials._header', [
        'title' => trans('wiki::messages.search.results'),
        'icon' => 'bi-search',
        'breadcrumb' => collect(),
    ])

    <div id="wiki" class="mx-auto" style="max-width: 860px">
        @forelse($pages as $page)
            <a href="{{ route('wiki.pages.show', [$page->category, $page]) }}" class="k-box k-result">
                <span class="k-tag"><i class="{{ $page->category->icon ?? 'bi bi-book' }}"></i> {{ $page->category->name }}</span>
                <h2 class="mt-2">{{ $page->title }}</h2>
                <p>{{ Str::limit(strip_tags($page->content), 260) }}</p>
            </a>
        @empty
            <div class="k-box"><p class="k-empty"><i class="bi bi-search"></i> {{ trans('wiki::messages.search.empty') }}</p></div>
        @endforelse

        {{ $pages->withQueryString()->links() }}
    </div>
@endsection
