{{-- Barre latérale de la boutique : compte + catégories --}}
@php
    $koriaCategory = $category ?? null;
@endphp

<div class="ks-wood">
    @if($shopUser !== null)
        <div class="ks-hello">
            <img src="{{ $shopUser->getAvatar(64) }}" alt="" width="50" height="50">
            <div>
                <small>Bonjour,</small>
                <strong>{{ $shopUser->name }}</strong>
            </div>
        </div>

        @if(use_site_money())
            <div class="ks-balance">
                <img src="{{ theme_asset('img/coin.png') }}" alt="">
                <div>
                    <strong>{{ (float) $shopUser->money }}</strong>
                    <span data-keep-money>{{ money_name($shopUser->money) }}</span>
                </div>
            </div>
        @endif

        <div class="ks-actions">
            @if(use_site_money())
                <a href="{{ route('shop.offers.select') }}" class="ks-btn ks-btn-green">
                    <i class="bi bi-plus-circle-fill"></i> {{ trans('shop::messages.cart.credit') }}
                </a>
            @endif

            <a href="{{ route('shop.cart.index') }}" class="ks-btn">
                <i class="bi bi-cart-fill"></i> {{ trans('shop::messages.cart.title') }}
            </a>

            @if($userHasPayments ?? true)
                <a href="{{ route('shop.profile') }}" class="ks-btn">
                    <i class="bi bi-receipt"></i> {{ trans('shop::messages.profile.payments') }}
                </a>
            @endif

            @guest
                @if(Route::has('shop.logout'))
                    <form action="{{ route('shop.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="ks-btn"><i class="bi bi-box-arrow-right"></i> {{ trans('auth.logout') }}</button>
                    </form>
                @endif
            @endguest
        </div>
    @else
        <a href="{{ route('shop.login') }}" class="ks-btn ks-btn-green">
            <i class="bi bi-box-arrow-in-right"></i> {{ trans('auth.login') }}
        </a>
    @endif
</div>

<nav class="ks-wood ks-cats" aria-label="{{ trans('shop::messages.title') }}">
    @if($displayHome ?? false)
        <a href="{{ route('shop.home') }}" class="ks-cat @if($koriaCategory === null) is-active @endif">{{ trans('messages.home') }}</a>
    @endif

    @foreach($categories as $subCategory)
        <a href="{{ route('shop.categories.show', $subCategory) }}" class="ks-cat @if($subCategory->is($koriaCategory)) is-active @endif">{{ $subCategory->name }}</a>

        @foreach($subCategory->categories ?? [] as $cat)
            <a href="{{ route('shop.categories.show', $cat) }}" class="ks-cat ks-cat-sub @if($cat->is($koriaCategory)) is-active @endif">{{ $cat->name }}</a>
        @endforeach
    @endforeach
</nav>

@if(isset($goalTiers) && ! $goalTiers->isEmpty())
    @php
        $nextTier = $goalTiers->first(fn ($tier) => ! $tier->isReached());
        $progress = $nextTier?->progress($goalTotal) ?? 100;
    @endphp
    <div class="ks-intro">
        <p class="fw-bold mb-2"><i class="bi bi-flag-fill"></i> {{ trans('shop::messages.goal.title') }}</p>
        <div class="progress mb-2"><div class="progress-bar" style="width: {{ $progress }}%"></div></div>
        <p class="small fw-semibold mb-0">
            @if($nextTier === null)
                {{ trans('shop::messages.goal.completed') }}
            @else
                {{ trans('shop::messages.goal.progress_tier', ['progress' => $progress, 'tier' => $nextTier->name]) }}
            @endif
        </p>
    </div>
@endif

@if(($topCustomer ?? null) !== null)
    <div class="ks-intro d-flex align-items-center gap-3">
        <img src="{{ $topCustomer->user->getAvatar(48) }}" alt="" width="44" class="rounded">
        <div>
            <p class="fw-bold mb-0"><i class="bi bi-trophy-fill"></i> {{ trans('shop::messages.top.title') }}</p>
            {{ $topCustomer->user->name }}
        </div>
    </div>
@endif

@if(($recentPayments ?? null) !== null && count($recentPayments))
    <div class="ks-intro">
        <p class="fw-bold mb-2"><i class="bi bi-clock-history"></i> {{ trans('shop::messages.recent.title') }}</p>
        @foreach($recentPayments as $payment)
            <div class="small fw-semibold">{{ $payment->user->name }} · {{ format_date($payment->created_at) }}</div>
        @endforeach
    </div>
@endif
