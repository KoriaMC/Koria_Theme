{{-- Sommaire du wiki --}}
@php($koriaTree = $tree ?? $categories ?? collect([$page->category]))

<nav class="k-panel k-wood k-wiki-nav" aria-label="{{ trans('wiki::messages.title') }}">
    <p class="k-panel-title"><i class="bi bi-journal-bookmark-fill"></i> Sommaire</p>

    @foreach($koriaTree as $category)
        @include('wiki::partials._sidebar-category', ['category' => $category])
    @endforeach
</nav>
