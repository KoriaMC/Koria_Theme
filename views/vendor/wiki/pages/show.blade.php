@extends('layouts.app')

@section('title', $page->title)

@section('content')
    @include('wiki::partials._header', [
        'title' => $page->title,
        'breadcrumb' => $breadcrumb ?? collect([$page->category]),
        'icon' => \Illuminate\Support\Str::after($page->category->icon ?? 'bi bi-book-half', 'bi '),
    ])

    <div class="row g-4" id="wiki">
        <div class="col-lg-3">
            @include('wiki::partials._sidebar')
        </div>

        <div class="col-lg-9">
            <article class="k-box">
                <div class="k-box-body k-prose p-lg-4">
                    {!! $page->content !!}
                </div>
            </article>

            @if(($previous ?? null) !== null || ($next ?? null) !== null)
                <nav class="k-pager" aria-label="Pages">
                    @if(($previous ?? null) !== null)
                        <a href="{{ route('wiki.pages.show', [$page->category, $previous]) }}" rel="prev">
                            <small>@lang('pagination.previous')</small>
                            <strong>{{ $previous->title }}</strong>
                        </a>
                    @endif
                    @if(($next ?? null) !== null)
                        <a href="{{ route('wiki.pages.show', [$page->category, $next]) }}" class="is-next" rel="next">
                            <small>@lang('pagination.next')</small>
                            <strong>{{ $next->title }}</strong>
                        </a>
                    @endif
                </nav>
            @endif
        </div>
    </div>
@endsection
