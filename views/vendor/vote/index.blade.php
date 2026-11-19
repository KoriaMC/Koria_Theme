@extends('layouts.app')

@section('title', trans('vote::messages.title'))

@php
    $koriaTop = collect($votes);
    $koriaPodium = $koriaTop->take(3);
    $koriaRest = $koriaTop->slice(3);
@endphp

@section('content')
    {{-- ===== Bannière + boutons de vote ===== --}}
    <section class="kw-hero ks-wood kv-hero">
        <span class="kw-hero-badge" aria-hidden="true">
            <span class="k-hex">
                <svg viewBox="0 0 120 128">
                    <polygon class="k-hex-frame" points="60,4 112,33 112,95 60,124 8,95 8,33"/>
                    <polygon class="k-hex-inner" points="60,18 99,40 99,88 60,110 21,88 21,40"/>
                    <path class="k-hex-shine" d="M21 40 L60 18 L99 40 L99 56 Q60 44 21 56 Z"/>
                </svg>
                <i class="bi bi-hand-thumbs-up-fill"></i>
            </span>
        </span>

        <div class="kw-hero-body">
            <span class="k-sign"><i class="bi bi-heart-fill"></i> Soutiens l'île</span>
            <h1 class="kw-hero-title">{{ trans('vote::messages.sections.vote') }}</h1>
            <p class="kw-hero-text">Vote pour {{ site_name() }} et gagne une récompense en jeu à chaque vote&nbsp;!</p>

            <div class="kv-card" id="vote-card">
                <div class="spinner-parent h-100">
                    <div class="spinner-border text-white" role="status"></div>
                </div>

                <div class="@auth d-none @endauth" data-vote-step="1">
                    <form action="{{ route('vote.verify-user', '/') }}" id="voteNameForm">
                        @if(!$authRequired)
                            <div class="kv-name">
                                <input type="text" id="stepNameInput" name="name" class="form-control"
                                       value="{{ $name }}"
                                       placeholder="Ton pseudo Minecraft" required>
                                <button type="submit" class="k-btn">
                                    {{ trans('messages.actions.continue') }}
                                    <span class="d-none spinner-border spinner-border-sm load-spinner" role="status"></span>
                                </button>
                            </div>
                        @else
                            <p class="kv-login-text"><i class="bi bi-person-circle"></i> Connecte-toi pour voter et recevoir tes récompenses.</p>
                            <a href="{{ route('login') }}" class="k-btn k-btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> {{ trans('auth.login') }}
                            </a>
                        @endif
                    </form>
                </div>

                <div class="@guest d-none @endguest" data-vote-step="2">
                    <div class="kv-sites">
                        @forelse($sites as $site)
                            <a class="k-btn k-btn-lg kv-site" href="{{ $site->url }}" target="_blank" rel="noopener noreferrer"
                               data-vote-id="{{ $site->id }}"
                               data-vote-url="{{ route('vote.vote', $site) }}"
                               @auth data-vote-time="{{ $site->getNextVoteTime($user, $request)?->valueOf() }}" @endauth>
                                <i class="bi bi-box-arrow-up-right"></i> {{ $site->name }}
                                <span class="kv-timer vote-timer"></span>
                            </a>
                        @empty
                            <p class="kv-empty"><i class="bi bi-hourglass-split"></i> {{ trans('vote::messages.errors.site') }}</p>
                        @endforelse
                    </div>
                </div>

                <div class="d-none" data-vote-step="server">
                    <p class="kv-login-text">{{ trans('vote::messages.server') }}</p>
                    <div id="server-select" class="kv-sites"></div>
                </div>
            </div>

            <div class="kw-hero-stats">
                @auth
                    <span><i class="bi bi-check2-circle"></i> {{ trans_choice('vote::messages.votes', $userVotes) }}</span>
                @endauth
                <span><i class="bi bi-gift"></i> Une récompense à chaque vote</span>
            </div>
        </div>
    </section>

    {{-- ===== Comment ça marche ===== --}}
    <ol class="kv-steps">
        <li class="kw-c0"><span class="kv-step-icon"><i class="bi bi-hand-index-thumb"></i></span><div><strong>1. Clique</strong><span>sur un site de vote ci-dessus</span></div></li>
        <li class="kw-c1"><span class="kv-step-icon"><i class="bi bi-check2-square"></i></span><div><strong>2. Vote</strong><span>avec ton pseudo sur le site qui s'ouvre</span></div></li>
        <li class="kw-c3"><span class="kv-step-icon"><i class="bi bi-gift-fill"></i></span><div><strong>3. Reçois</strong><span>ta récompense directement en jeu</span></div></li>
    </ol>

    @if($goalEnabled)
        <section class="kv-panel kv-goal" id="vote-goal">
            <h2 class="kv-title"><i class="bi bi-flag-fill"></i> {{ trans('vote::messages.sections.goal') }}</h2>
            <div class="progress kv-progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated"
                     role="progressbar"
                     style="width: {{ min($goalPercentage, 100) }}%"
                     aria-valuenow="{{ $goalProgress }}"
                     aria-valuemin="0"
                     aria-valuemax="{{ $goalTarget }}">
                </div>
            </div>
            <p class="kv-goal-text" id="goal-text">
                {{ trans('vote::messages.goal', ['current' => $goalProgress, 'target' => $goalTarget]) }}
            </p>
        </section>
    @endif

    <div class="kv-grid @if(!$displayRewards) kv-grid-single @endif">
        {{-- ===== Classement ===== --}}
        <section class="kv-panel">
            <h2 class="kv-title"><i class="bi bi-trophy-fill"></i> {{ trans('vote::messages.sections.top') }}</h2>

            @if($koriaTop->isEmpty())
                <p class="kv-empty"><i class="bi bi-emoji-smile"></i> Personne n'a encore voté ce mois-ci. Sois le premier !</p>
            @else
                <div class="kv-podium">
                    @foreach($koriaPodium as $id => $vote)
                        <div class="kv-place kv-place-{{ $loop->iteration }}">
                            <img src="{{ $vote->user->getAvatar(96) }}" alt="{{ $vote->user->name }}" class="kv-avatar">
                            <strong class="kv-place-name">{{ $vote->user->name }}</strong>
                            <span class="kv-place-votes">{{ $vote->votes }} {{ $vote->votes > 1 ? 'votes' : 'vote' }}</span>
                            <span class="kv-block">#{{ $id }}</span>
                        </div>
                    @endforeach
                </div>

                @if($koriaRest->isNotEmpty())
                    <ol class="kv-list">
                        @foreach($koriaRest as $id => $vote)
                            <li>
                                <span class="kv-rank">#{{ $id }}</span>
                                <img src="{{ $vote->user->getAvatar(48) }}" alt="" class="kv-avatar-sm">
                                <span class="kv-list-name">{{ $vote->user->name }}</span>
                                <span class="kv-list-votes">{{ $vote->votes }}</span>
                            </li>
                        @endforeach
                    </ol>
                @endif
            @endif
        </section>

        {{-- ===== Récompenses (sans les chances) ===== --}}
        @if($displayRewards)
            <section class="kv-panel">
                <h2 class="kv-title"><i class="bi bi-gift-fill"></i> {{ trans('vote::messages.sections.rewards') }}</h2>
                <p class="kv-sub">À chaque vote, tu gagnes une de ces récompenses au hasard :</p>

                <div class="kv-rewards">
                    @forelse($rewards as $reward)
                        <div class="kv-reward kw-c{{ $loop->index % 5 }}">
                            @if($reward->image)
                                <img src="{{ $reward->imageUrl() }}" alt="" class="kv-reward-img">
                            @endif
                            <span>{{ $reward->name }}</span>
                        </div>
                    @empty
                        <p class="kv-empty">Les récompenses arrivent bientôt.</p>
                    @endforelse
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
    @include('vote::partials._scripts')
@endpush

@push('styles')
    <link href="{{ theme_asset('css/vote.css') }}&t={{ @filemtime(resource_path('themes/koria/assets/css/vote.css')) }}" rel="stylesheet">
@endpush
