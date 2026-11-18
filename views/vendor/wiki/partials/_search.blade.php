{{-- Barre de recherche du wiki --}}
<form class="kw-search {{ $searchClass ?? '' }}" action="{{ route('wiki.search') }}" method="GET" role="search">
    <label class="visually-hidden" for="wikiSearch">{{ trans('messages.actions.search') }}</label>
    <i class="bi bi-search kw-search-icon" aria-hidden="true"></i>
    <input type="search" id="wikiSearch" name="q" value="{{ $search ?? '' }}"
           placeholder="{{ $searchPlaceholder ?? 'Rechercher dans le wiki…' }}" required>
    <button type="submit">{{ trans('messages.actions.search') }}</button>
</form>
