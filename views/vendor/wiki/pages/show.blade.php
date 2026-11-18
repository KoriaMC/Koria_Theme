@extends('layouts.app')

@section('title', $page->title)

@section('content')
    @include('wiki::partials._header', ['title' => $page->title, 'breadcrumb' => $breadcrumb])

    <div class="row g-4" id="wiki">
        <div class="col-lg-3 order-2 order-lg-1">
            @include('wiki::partials._sidebar')
        </div>

        <div class="col-lg-9 order-1 order-lg-2">
            <article class="kw-article">
                <span class="kw-tag"><i class="{{ $page->category->icon ?? 'bi bi-book' }}"></i> {{ $page->category->name }}</span>

                <div class="kw-prose">
                    {!! $page->content !!}
                </div>
            </article>

            @if($previous !== null || $next !== null)
                <nav class="kw-pager" aria-label="Pages">
                    @if($previous !== null)
                        <a href="{{ route('wiki.pages.show', [$page->category, $previous]) }}" rel="prev">
                            <i class="bi bi-arrow-left-circle-fill"></i>
                            <span>
                                <small>@lang('pagination.previous')</small>
                                <strong>{{ $previous->title }}</strong>
                            </span>
                        </a>
                    @endif

                    @if($next !== null)
                        <a href="{{ route('wiki.pages.show', [$page->category, $next]) }}" class="is-next" rel="next">
                            <span>
                                <small>@lang('pagination.next')</small>
                                <strong>{{ $next->title }}</strong>
                            </span>
                            <i class="bi bi-arrow-right-circle-fill"></i>
                        </a>
                    @endif
                </nav>
            @endif
        </div>
    </div>
@endsection
