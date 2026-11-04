@extends('layouts.base')

@section('title', trans('messages.home'))
@section('body-class', 'k-home')

@php
    $k = app()->bound('koria.theme') ? app('koria.theme') : app()->instance('koria.theme', require resource_path('themes/koria/config/helpers.php'));
    $launcher = $k->launcher();
    $launcherHref = $launcher ?: '#rejoindre';
    $otherOs = array_filter(['macOS' => $k->get('launcher_mac_url'), 'Linux' => $k->get('launcher_linux_url')]);
    $discord = $k->get('discord');
    $logo = $k->get('hero_logo') ? image_url($k->get('hero_logo')) : (setting('logo') ? image_url(setting('logo')) : theme_asset('img/logo.webp'));
    $background = $k->get('hero_background') ?: setting('background');
    $background = $background ? image_url($background) : null;
@endphp

@section('app')
    {{-- ===== En-tête ===== --}}
    <section class="k-hero @if($background) k-hero-has-img @endif" @if($background) style="--k-hero-img: url('{{ $background }}')" @endif>
        @if($k->on('hero_canopy'))
            @foreach(['tl', 'tr', 'bl', 'br'] as $corner)
                <div class="k-canopy k-canopy-{{ $corner }}" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
            @endforeach
        @endif

        <div class="k-hero-content">
            <h1 class="visually-hidden">{{ site_name() }}</h1>
            <img src="{{ $logo }}" alt="{{ site_name() }}" class="k-hero-logo">

            @if($k->on('hero_sign_enabled') && $k->get('hero_sign'))
                <span class="k-sign"><i class="bi bi-shield-fill"></i> {{ $k->get('hero_sign') }}</span>
            @endif

            @if($k->get('hero_subtitle'))
                <p class="k-hero-subtitle k-cream-field">{{ $k->get('hero_subtitle') }}</p>
            @endif

            <a href="{{ $launcherHref }}" class="k-btn k-btn-xl">
                <i class="bi bi-play-fill"></i> {{ $k->get('hero_button') }}
            </a>
            @if($k->on('hero_show_players') && $server && $server->isOnline())
                <span class="k-online">
                    <span class="k-dot"></span>{{ trans_choice('messages.server.online', $server->getOnlinePlayers()) }}
                </span>
            @endif
        </div>

        @if($k->on('join_enabled'))
            <a href="#rejoindre" class="k-scroll" aria-label="Défiler vers le bas"><span></span></a>
        @endif
    </section>

    <div class="k-jungle">
        <div class="container position-relative">
            @include('elements.session-alerts')

            @if($message)
                <div class="k-card k-card-light my-4">
                    {{ $message }}
                </div>
            @endif
        </div>

        {{-- ===== Comment rejoindre ===== --}}
        @if($k->on('join_enabled'))
            <section class="k-section" id="rejoindre">
                <div class="container">
                    <div class="row align-items-center gy-5 @unless($k->on('launcher_image_enabled')) justify-content-center @endunless">
                        <div class="col-lg-6 k-reveal">
                            <h2 class="k-title">{{ $k->title('about_title') }}</h2>
                            @if($k->get('about_text'))
                                <p class="k-lead mb-4">{{ $k->get('about_text') }}</p>
                            @endif

                            <div class="k-card k-wood k-leafy">
                                <h3 class="k-subtitle k-subtitle-wood mb-3">{{ $k->get('join_card_title') }}</h3>
                                <ol class="k-join-steps">
                                    @foreach($k->get('steps') as $step)
                                        <li class="k-cream-field">
                                            <span class="k-step-num">{{ $loop->iteration }}</span>
                                            <div>
                                                <strong>{{ $step['title'] }}</strong>
                                                @if($step['text'])<span>{{ $step['text'] }}</span>@endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>

                                <a href="{{ $launcherHref }}" class="k-btn w-100">
                                    <i class="bi bi-download"></i>
                                    {{ $launcher ? 'Télécharger pour Windows' : 'Launcher bientôt disponible' }}
                                </a>
                                @if($otherOs)
                                    <p class="k-other-os">
                                        Aussi disponible pour
                                        @foreach($otherOs as $os => $url)
                                            <a href="{{ $url }}">{{ $os }}</a>@if(! $loop->last) et @endif
                                        @endforeach
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if($k->on('launcher_image_enabled'))
                            <div class="col-lg-6 k-reveal k-reveal-right">
                                <figure class="k-launcher-shot mb-0">
                                    <img src="{{ $k->image('launcher_image', 'img/launcher-preview.webp') }}" alt="Aperçu du launcher {{ site_name() }}" loading="lazy">
                                    @if($k->get('launcher_caption'))
                                        <figcaption class="k-sign"><i class="bi bi-display"></i> {{ $k->get('launcher_caption') }}</figcaption>
                                    @endif
                                </figure>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        {{-- ===== Serveurs ===== --}}
        @if($k->on('servers_enabled') && ! $servers->isEmpty())
            <section class="k-section pt-0">
                <div class="container">
                    <h2 class="k-title text-center mb-5 k-reveal">{{ $k->title('servers_title') }}</h2>
                    <div class="row gy-4 justify-content-center">
                        @foreach($servers as $srv)
                            <div class="col-md-6 col-lg-4 k-reveal">
                                <div class="k-card k-card-light k-server h-100">
                                    <h3>{{ $srv->name }}</h3>
                                    @if($srv->isOnline())
                                        <div class="k-progress"><span style="width: {{ $srv->getPlayersPercents() }}%"></span></div>
                                        <p class="mb-0">
                                            {{ trans_choice('messages.server.total', $srv->getOnlinePlayers(), ['max' => $srv->getMaxPlayers()]) }}
                                        </p>
                                    @else
                                        <p class="mb-0"><span class="k-pill k-pill-off">{{ trans('messages.server.offline') }}</span></p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- ===== Points forts ===== --}}
        @if($k->on('features_enabled'))
            @php($features = array_values(array_filter($k->get('features'), fn ($f) => filled($f['title']))))
            <section class="k-section">
                <div class="container">
                    <h2 class="k-title text-center k-reveal">{{ $k->title('features_title') }}</h2>
                    <div class="row gy-5 mt-3 text-center justify-content-center">
                        @foreach($features as $feature)
                            <div class="col-sm-6 col-lg-3 k-feature k-reveal" style="--k-delay: {{ $loop->index * 120 }}ms">
                                <div class="k-hex">
                                    <svg viewBox="0 0 120 128" aria-hidden="true">
                                        <polygon class="k-hex-frame" points="60,4 112,33 112,95 60,124 8,95 8,33"/>
                                        <polygon class="k-hex-inner" points="60,18 99,40 99,88 60,110 21,88 21,40"/>
                                        <path class="k-hex-shine" d="M21 40 L60 18 L99 40 L99 56 Q60 44 21 56 Z"/>
                                    </svg>
                                    <i class="bi {{ $feature['icon'] ?: 'bi-star-fill' }}"></i>
                                </div>
                                <h3 class="k-feature-title">{{ $feature['title'] }}</h3>
                                <p class="k-feature-text">{{ $feature['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- ===== Actualités ===== --}}
        @if($k->on('news_enabled'))
            <section class="k-section">
                <div class="container">
                    <h2 class="k-title text-center mb-5 k-reveal">{{ $k->title('news_title') }}</h2>

                    @if($posts->isEmpty())
                        <div class="k-card k-card-light text-center k-reveal">
                            <i class="bi bi-newspaper fs-1 d-block mb-2 k-text-leaf"></i>
                            <strong>{{ $k->get('news_empty') }}</strong>
                        </div>
                    @else
                        <div class="row gy-4 justify-content-center">
                            @foreach($posts->take($k->get('news_count')) as $post)
                                <div class="col-md-6 col-lg-4 k-reveal" style="--k-delay: {{ $loop->index * 120 }}ms">
                                    <a href="{{ route('posts.show', $post) }}" class="k-post">
                                        <div class="k-post-img">
                                            @if($post->hasImage())
                                                <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}">
                                            @else
                                                <span class="k-post-placeholder"><i class="bi bi-image"></i></span>
                                            @endif
                                        </div>
                                        <h3 class="k-post-title">{{ $post->title }}</h3>
                                        <p class="k-post-date">{{ format_date($post->published_at) }}</p>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-5">
                            <a href="{{ route('posts.index') }}" class="k-btn k-btn-wood">Toutes les actualités</a>
                        </div>
                    @endif
                </div>
            </section>
        @endif
    </div>

    {{-- ===== Eau / Discord / Appel final ===== --}}
    @php($showDiscord = $discord && $k->on('discord_enabled'))
    @if($showDiscord || $k->on('cta_enabled'))
        <div class="k-ocean">
            <svg class="k-wave" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true">
                <path class="k-wave-shallow" d="M0 0 H1440 V86 C 1300 60 1140 112 960 86 S 600 54 420 86 S 140 108 0 86 Z"/>
                <path class="k-wave-foam" d="M0 0 H1440 V66 C 1300 40 1140 92 960 66 S 600 34 420 66 S 140 88 0 66 Z"/>
                <path class="k-wave-sand" d="M0 0 H1440 V52 C 1300 28 1140 76 960 52 S 600 22 420 52 S 140 72 0 52 Z"/>
            </svg>
            <span class="k-bubble k-bubble-1" aria-hidden="true"></span>
            <span class="k-bubble k-bubble-2" aria-hidden="true"></span>
            <span class="k-bubble k-bubble-3" aria-hidden="true"></span>

            @if($showDiscord)
                <section class="k-section @unless($k->on('cta_enabled')) k-cta @endunless">
                    <div class="container">
                        <div class="k-card k-card-deep k-reveal">
                            <div class="row align-items-center gy-4">
                                <div class="col-md-5 text-center">
                                    <div class="k-discord-art" aria-hidden="true"><i class="bi bi-discord"></i></div>
                                </div>
                                <div class="col-md-7">
                                    <h2 class="k-title">{{ $k->title('discord_title') }}</h2>
                                    @if($k->get('discord_text'))
                                        <p class="k-lead k-lead-light">{{ $k->get('discord_text') }}</p>
                                    @endif
                                    <a href="{{ $discord }}" class="k-btn k-btn-discord k-btn-lg" target="_blank" rel="noopener noreferrer">
                                        {{ $k->get('discord_button') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            @if($k->on('cta_enabled'))
                <section class="k-section k-cta">
                    <div class="k-plants" aria-hidden="true">
                        <span></span><span></span><span></span><span></span><span></span><span></span>
                    </div>
                    <div class="container text-center position-relative">
                        <h2 class="k-title k-reveal">{{ $k->title('cta_title') }}</h2>
                        @if($k->get('cta_text'))
                            <p class="k-lead k-lead-light mx-auto k-reveal" style="max-width: 560px">{{ $k->get('cta_text') }}</p>
                        @endif
                        <div class="d-flex flex-wrap justify-content-center gap-3 mt-4 k-reveal">
                            <a href="{{ $launcherHref }}" class="k-btn k-btn-lg">
                                <i class="bi bi-download"></i> {{ $k->get('cta_button') }}
                            </a>
                            @if($discord)
                                <a href="{{ $discord }}" class="k-btn k-btn-discord k-btn-lg" target="_blank" rel="noopener noreferrer">
                                    <i class="bi bi-discord"></i> Discord
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="k-rocks" aria-hidden="true"></div>
                </section>
            @endif
        </div>
    @endif
@endsection
