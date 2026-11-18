{{-- En-tête des pages du wiki : fil d'Ariane, titre et recherche --}}
<header class="kw-head">
    <div class="kw-head-text">
        <nav aria-label="breadcrumb">
            <ol class="kw-crumbs">
                <li><a href="{{ route('wiki.index') }}"><i class="bi bi-book-half"></i> {{ trans('wiki::messages.title') }}</a></li>
                @foreach($breadcrumb ?? [] as $item)
                    <li><a href="{{ route('wiki.show', $item) }}">{{ $item->name }}</a></li>
                @endforeach
            </ol>
        </nav>

        <h1>{{ $title }}</h1>
    </div>

    @include('wiki::partials._search')
</header>
