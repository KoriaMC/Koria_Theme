@extends('layouts.app')

@section('title', trans('vote::messages.title'))

@section('content')
    @include('elements.page-head', [
        'title' => trans('vote::messages.sections.vote'),
        'icon' => 'bi-hand-thumbs-up-fill',
        'subtitle' => 'Vote pour KoriaMC sur chaque site et récupère des récompenses en jeu. Merci, ça nous aide énormément !',
    ])

    <ol class="k-vote-steps p-0">
        <li class="k-cream-field">
            <span class="k-step-num">1</span>
            <span><strong>Choisis un site</strong><span>Clique sur un des boutons ci-dessous.</span></span>
        </li>
        <li class="k-cream-field">
            <span class="k-step-num">2</span>
            <span><strong>Vote sur le site</strong><span>Valide ton vote dans l'onglet qui s'ouvre.</span></span>
        </li>
        <li class="k-cream-field">
            <span class="k-step-num">3</span>
            <span><strong>Reviens ici</strong><span>Ta récompense arrive automatiquement en jeu.</span></span>
        </li>
    </ol>

    <div class="k-wood k-leafy mb-5" id="vote-card">
        <div class="spinner-parent">
            <div class="spinner-border text-white" role="status"><span class="visually-hidden">Chargement…</span></div>
        </div>

        <div class="@auth d-none @endauth" data-vote-step="1">
            <form action="{{ route('vote.verify-user', '/') }}" id="voteNameForm">
                @if(! ($authRequired ?? false))
                    <div class="k-vote-name d-grid gap-3">
                        <label for="stepNameInput" class="k-panel-title justify-content-center m-0">
                            <i class="bi bi-person-fill"></i> Ton pseudo en jeu
                        </label>
                        <input type="text" id="stepNameInput" name="name" class="form-control form-control-lg text-center"
                               value="{{ $name ?? '' }}" placeholder="{{ trans('messages.fields.name') }}" required>

                        <button type="submit" class="k-btn k-btn-leaf k-btn-lg k-btn-block">
                            {{ trans('messages.actions.continue') }} <i class="bi bi-arrow-right"></i>
                            <span class="d-none spinner-border spinner-border-sm load-spinner" role="status"></span>
                        </button>
                    </div>
                @else
                    <div class="k-vote-name">
                        <p class="k-panel-title justify-content-center"><i class="bi bi-lock-fill"></i> {{ trans('vote::messages.errors.auth') }}</p>
                        <a href="{{ route('login') }}" class="k-btn k-btn-leaf k-btn-lg k-btn-block">
                            <i class="bi bi-box-arrow-in-right"></i> {{ trans('auth.login') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <div class="@guest d-none @endguest" data-vote-step="2">
            @if(count($sites))
                <div class="k-vote-sites">
                    @foreach($sites as $site)
                        <a class="k-btn k-btn-cream k-vote-site" href="{{ $site->url }}" target="_blank" rel="noopener noreferrer"
                           data-vote-id="{{ $site->id }}"
                           data-vote-url="{{ route('vote.vote', $site) }}"
                           @auth data-vote-time="{{ $site->getNextVoteTime($user, $request)?->valueOf() }}" @endauth>
                            <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                            {{ $site->name }}
                            <span class="vote-timer"></span>
                        </a>
                    @endforeach
                </div>
                <p class="k-vote-hint"><i class="bi bi-clock-fill"></i> Un compte à rebours s'affiche sur les sites où tu as déjà voté.</p>
            @else
                <p class="k-panel-title justify-content-center m-0">
                    <i class="bi bi-exclamation-circle-fill"></i> {{ trans('vote::messages.errors.site') }}
                </p>
            @endif
        </div>

        <div class="d-none" data-vote-step="server">
            <p class="k-panel-title justify-content-center">{{ trans('vote::messages.server') }}</p>
            <div id="server-select"></div>
        </div>
    </div>

    @if($goalEnabled ?? false)
        <section class="k-box" id="vote-goal">
            <h2 class="k-box-title"><i class="bi bi-flag-fill"></i> {{ trans('vote::messages.sections.goal') }}</h2>
            <div class="k-box-body">
                <div class="k-goal-bar">
                    <div class="progress-bar" role="progressbar" style="width: {{ min($goalPercentage, 100) }}%"
                         aria-valuenow="{{ $goalProgress }}" aria-valuemin="0" aria-valuemax="{{ $goalTarget }}"></div>
                </div>
                <p class="k-goal-text" id="goal-text">
                    {{ trans('vote::messages.goal', ['current' => $goalProgress, 'target' => $goalTarget]) }}
                </p>
            </div>
        </section>
    @endif

    <div class="row g-4">
        <div class="{{ ($displayRewards ?? false) && count($rewards ?? []) ? 'col-lg-7' : 'col-12' }}">
            <section class="k-box h-100 mb-0">
                <h2 class="k-box-title"><i class="bi bi-trophy-fill"></i> {{ trans('vote::messages.sections.top') }}</h2>
                <div class="k-box-body">
                    @php
                        $koriaVotes = collect($votes);
                        $koriaPodium = $koriaVotes->take(3)->values();
                        $koriaRest = $koriaVotes->slice(3);
                    @endphp

                    @if($koriaVotes->isEmpty())
                        <p class="k-empty"><i class="bi bi-emoji-smile"></i> Personne n'a encore voté ce mois-ci. Sois le premier !</p>
                    @else
                        <div class="k-podium">
                            @foreach([1, 0, 2] as $podiumIndex)
                                @if($vote = $koriaPodium->get($podiumIndex))
                                    <div class="k-podium-step k-podium-{{ $podiumIndex + 1 }}">
                                        @if($podiumIndex === 0)
                                            <i class="bi bi-award-fill k-podium-crown" aria-hidden="true"></i>
                                        @endif
                                        <img src="{{ $vote->user->getAvatar(96) }}" alt="">
                                        <div class="k-podium-name">{{ $vote->user->name }}</div>
                                        <div class="k-podium-votes">{{ $vote->votes }} votes</div>
                                        <div class="k-podium-block">{{ $podiumIndex + 1 }}</div>
                                    </div>
                                @else
                                    <div></div>
                                @endif
                            @endforeach
                        </div>

                        @if($koriaRest->isNotEmpty())
                            <ol class="k-ranking">
                                @foreach($koriaRest as $id => $vote)
                                    <li>
                                        <span class="k-rank">#{{ $id }}</span>
                                        <span class="k-rank-name">{{ $vote->user->name }}</span>
                                        <span class="k-rank-votes">{{ $vote->votes }} <i class="bi bi-hand-thumbs-up-fill"></i></span>
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    @endif

                    @auth
                        <p class="k-my-votes"><i class="bi bi-person-check-fill"></i> {{ trans_choice('vote::messages.votes', $userVotes) }}</p>
                    @endauth
                </div>
            </section>
        </div>

        @if(($displayRewards ?? false) && count($rewards ?? []))
            <div class="col-lg-5">
                <section class="k-box h-100 mb-0">
                    <h2 class="k-box-title"><i class="bi bi-gift-fill"></i> {{ trans('vote::messages.sections.rewards') }}</h2>
                    <div class="k-box-body">
                        <div class="k-rewards">
                            @foreach($rewards as $reward)
                                <div class="k-reward">
                                    @if($reward->image)
                                        <img src="{{ $reward->imageUrl() }}" alt="">
                                    @else
                                        <i class="bi bi-box2-heart-fill" aria-hidden="true"></i>
                                    @endif
                                    <strong>{{ $reward->name }}</strong>
                                    <span class="k-chance">{{ $reward->chances }} %</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    @include('vote::partials._scripts')
@endpush
