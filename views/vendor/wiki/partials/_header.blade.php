{{-- En-tête du wiki : panneau en bois + barre de recherche --}}
@php
    $koriaCrumbs = [];
    if (isset($breadcrumb)) {
        $koriaCrumbs[] = [trans('wiki::messages.title'), route('wiki.index')];
        foreach ($breadcrumb as $item) {
            $koriaCrumbs[] = [$item->name, route('wiki.show', $item)];
        }
    }
@endphp

@include('elements.page-head', [
    'title' => $title,
    'icon' => $icon ?? 'bi-book-half',
    'subtitle' => $subtitle ?? null,
    'crumbs' => $koriaCrumbs,
])

@if(Route::has('wiki.search'))
    <form class="k-search" action="{{ route('wiki.search') }}" method="GET" role="search">
        <label class="visually-hidden" for="searchInput">{{ trans('messages.actions.search') }}</label>
        <div class="input-group">
            <input type="search" class="form-control" id="searchInput" name="q" value="{{ $search ?? '' }}"
                   placeholder="Rechercher dans le wiki…" required>
            <button type="submit" class="k-btn k-btn-leaf" aria-label="{{ trans('messages.actions.search') }}">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>
@endif
