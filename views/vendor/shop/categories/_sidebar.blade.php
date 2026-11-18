{{-- Barre latérale de la boutique (catégories, compte, objectif, meilleurs acheteurs) --}}
@php
    $koriaCategory = $category ?? null;
    $koriaShopUser = $shopUser ?? auth()->user();
    $koriaShowAmount = $displaySidebarAmount ?? true;
@endphp

<div class="k-panel k-wood">
    <p class="k-panel-title"><i class="bi bi-grid-3x3-gap-fill"></i> Catégories</p>

    <nav class="k-cats" aria-label="{{ trans('shop::messages.title') }}">
        @if($displayHome ?? true)
            <a href="{{ route('shop.home') }}" class="k-cat @if($koriaCategory === null) is-active @endif">
                <i class="bi bi-house-door-fill"></i> {{ trans('messages.home') }}
            </a>
        @endif

        @foreach($categories as $subCategory)
            <a href="{{ route('shop.categories.show', $subCategory) }}" class="k-cat @if($subCategory->is($koriaCategory)) is-active @endif">
                <i class="{{ $subCategory->icon ?: 'bi bi-box-seam' }}"></i> {{ $subCategory->name }}
            </a>

            @foreach($subCategory->categories ?? [] as $cat)
                <a href="{{ route('shop.categories.show', $cat) }}" class="k-cat k-cat-sub @if($cat->is($koriaCategory)) is-active @endif">
                    <i class="{{ $cat->icon ?: 'bi bi-arrow-return-right' }}"></i> {{ $cat->name }}
                </a>
            @endforeach
        @endforeach
    </nav>
</div>

<div class="k-box">
    <div class="k-box-body">
        @if($koriaShopUser !== null)
            <div class="k-me">
                <img src="{{ $koriaShopUser->getAvatar(64) }}" alt="" width="52" height="52">
                <div>
                    <p class="k-me-name">{{ $koriaShopUser->name }}</p>
                    @if(use_site_money())
                        <span class="k-money" title="{{ trans('shop::messages.profile.money', ['balance' => format_money($koriaShopUser->money)]) }}">
                            <i class="bi bi-coin"></i> {{ format_money($koriaShopUser->money) }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="k-me-actions">
                @if(use_site_money())
                    <a href="{{ route('shop.offers.select') }}" class="k-btn k-btn-leaf k-btn-block">
                        <i class="bi bi-plus-circle-fill"></i> {{ trans('shop::messages.cart.credit') }}
                    </a>
                @endif

                <a href="{{ route('shop.cart.index') }}" class="k-btn k-btn-wood k-btn-block">
                    <i class="bi bi-basket3-fill"></i> {{ trans('shop::messages.cart.title') }}
                </a>

                @if($userHasPayments ?? false)
                    <a href="{{ route('shop.profile') }}" class="k-btn k-btn-cream k-btn-block">
                        <i class="bi bi-receipt"></i> {{ trans('shop::messages.profile.payments') }}
                    </a>
                @endif

                @guest
                    @if(Route::has('shop.logout'))
                        <form action="{{ route('shop.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="k-btn k-btn-cream k-btn-block">
                                <i class="bi bi-box-arrow-right"></i> {{ trans('auth.logout') }}
                            </button>
                        </form>
                    @endif
                @endguest
            </div>
        @else
            <p class="fw-semibold text-center mb-3">Connecte-toi pour remplir ton panier et voir ton solde.</p>
            <a href="{{ Route::has('shop.login') ? route('shop.login') : route('login') }}" class="k-btn k-btn-leaf k-btn-block">
                <i class="bi bi-box-arrow-in-right"></i> {{ trans('auth.login') }}
            </a>
        @endif
    </div>
</div>

@if(isset($goalTiers) && ! $goalTiers->isEmpty())
    @php
        $nextTier = $goalTiers->first(fn ($tier) => ! $tier->isReached());
        $progress = $nextTier?->progress($goalTotal) ?? 100;
    @endphp

    <div class="k-box">
        <p class="k-box-title">
            <i class="bi bi-flag-fill"></i> {{ trans('shop::messages.goal.title') }}
            @if($goalTiers->count() > 1)
                <span class="ms-auto k-pill" style="background:var(--k-cream);color:var(--k-wood-deep)">{{ $goalTiers->filter->isReached()->count() }}/{{ $goalTiers->count() }}</span>
            @endif
        </p>
        <div class="k-box-body">
            <div class="k-goal-bar" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" style="width: {{ $progress }}%"></div>
            </div>
            <p class="k-goal-text">
                @if($nextTier === null)
                    {{ trans('shop::messages.goal.completed') }}
                @elseif($displayGoalAmount ?? false)
                    {{ trans('shop::messages.goal.remaining', ['amount' => $nextTier->formatRemaining($goalTotal), 'tier' => $nextTier->name]) }}
                @else
                    {{ trans('shop::messages.goal.progress_tier', ['progress' => $progress, 'tier' => $nextTier->name]) }}
                @endif
            </p>

            <ul class="k-goal-tiers">
                @foreach($goalTiers as $tier)
                    <li class="@if($tier->isReached()) is-reached @endif">
                        <i class="{{ $tier->icon ?: 'bi bi-gift-fill' }}"></i>
                        <span class="flex-grow-1">
                            {{ $tier->name }}
                            @if($displayGoalAmount ?? false)
                                <small class="d-block fw-semibold">{{ $tier->formatAmount() }}</small>
                            @endif
                        </span>
                        @if($tier->isReached())
                            <i class="bi bi-check-circle-fill text-success" title="{{ trans('shop::messages.goal.reached') }}"></i>
                        @else
                            <i class="bi bi-lock-fill opacity-50"></i>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@elseif(isset($goal) && $goal !== false && $goal !== null)
    <div class="k-box">
        <p class="k-box-title"><i class="bi bi-flag-fill"></i> {{ trans('shop::messages.goal.title') }}</p>
        <div class="k-box-body">
            <div class="k-goal-bar" role="progressbar" aria-valuenow="{{ $goal }}" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" style="width: {{ min($goal, 100) }}%"></div>
            </div>
            <p class="k-goal-text">{{ trans_choice('shop::messages.goal.progress', $goal) }}</p>
        </div>
    </div>
@endif

@if(($topCustomer ?? null) !== null)
    <div class="k-box">
        <p class="k-box-title"><i class="bi bi-trophy-fill"></i> {{ trans('shop::messages.top.title') }}</p>
        <div class="k-box-body k-top">
            <span class="k-top-avatar">
                <i class="bi bi-award-fill" aria-hidden="true"></i>
                <img src="{{ $topCustomer->user->getAvatar(64) }}" alt="" width="64" height="64">
            </span>
            <div>
                <p class="k-me-name mb-1">{{ $topCustomer->user->name }}</p>
                @if($koriaShowAmount)
                    <span class="fw-bold">{{ $topCustomer->formatPrice() }}</span>
                @endif
            </div>
        </div>
    </div>
@endif

@if(($recentPayments ?? null) !== null)
    <div class="k-box">
        <p class="k-box-title"><i class="bi bi-clock-history"></i> {{ trans('shop::messages.recent.title') }}</p>
        <ul class="k-people">
            @forelse($recentPayments as $payment)
                <li>
                    <img src="{{ $payment->user->getAvatar(48) }}" alt="" width="34" height="34">
                    <div>
                        <strong>{{ $payment->user->name }}</strong>
                        <small>
                            @if($koriaShowAmount){{ $payment->formatPrice() }} · @endif{{ format_date($payment->created_at) }}
                        </small>
                    </div>
                </li>
            @empty
                <li><small>{{ trans('shop::messages.recent.empty') }}</small></li>
            @endforelse
        </ul>
    </div>
@endif
