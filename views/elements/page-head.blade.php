{{--
    En-tête des pages internes : un panneau en bois suspendu.
    @include('elements.page-head', [
        'title' => 'Boutique',          // obligatoire
        'icon' => 'bi-cart3',           // icône Bootstrap Icons (optionnel)
        'subtitle' => '…',              // petite phrase sous le titre (optionnel)
        'crumbs' => [['Wiki', url]],    // fil d'Ariane (optionnel)
    ])
--}}
<header class="k-head">
    @if(! empty($crumbs))
        <nav aria-label="breadcrumb">
            <ol class="k-crumbs">
                @foreach($crumbs as [$crumbLabel, $crumbUrl])
                    <li><a href="{{ $crumbUrl }}">{{ $crumbLabel }}</a></li>
                @endforeach
            </ol>
        </nav>
    @endif

    <div class="k-head-sign k-wood">
        <span class="k-head-hex" aria-hidden="true">
            <svg viewBox="0 0 120 128">
                <polygon class="k-hex-frame" points="60,4 112,33 112,95 60,124 8,95 8,33"/>
                <polygon class="k-hex-inner" points="60,18 99,40 99,88 60,110 21,88 21,40"/>
                <path class="k-hex-shine" d="M21 40 L60 18 L99 40 L99 56 Q60 44 21 56 Z"/>
            </svg>
            <i class="bi {{ $icon ?? 'bi-signpost-2-fill' }}"></i>
        </span>
        <div>
            <h1>{{ $title }}</h1>
            @if(! empty($subtitle))
                <p class="k-head-sub">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</header>
