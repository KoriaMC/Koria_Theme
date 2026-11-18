{{-- Sommaire du wiki, dans un panneau en bois --}}
<nav class="ks-wood kw-nav" aria-label="{{ trans('wiki::messages.title') }}">
    <p class="kw-nav-title"><i class="bi bi-journal-bookmark-fill"></i> Sommaire</p>

    @foreach($tree as $category)
        @include('wiki::partials._sidebar-category', ['category' => $category])
    @endforeach
</nav>
