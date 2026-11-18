@extends('layouts.app')

@section('title', trans('shop::messages.cart.title'))

@section('content')
    @include('elements.page-head', [
        'title' => trans('shop::messages.cart.title'),
        'icon' => 'bi-basket3-fill',
        'crumbs' => [[trans('shop::messages.title'), route('shop.home')]],
    ])

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="k-box">
                <p class="k-box-title"><i class="bi bi-basket3-fill"></i> Tes articles</p>

                @if(! $cart->isEmpty())
                    <form action="{{ route('shop.cart.update') }}" method="POST" id="cartUpdateForm">
                        @csrf

                        @foreach($cart->content() as $cartItem)
                            <div class="k-cart-item">
                                <div class="k-cart-name">
                                    {{ $cartItem->name() }}
                                    <small>{{ shop_format_amount($cartItem->price()) }} l'unité</small>
                                </div>

                                <input type="number" min="0" max="{{ $cartItem->maxQuantity() }}" class="form-control form-control-sm"
                                       name="quantities[{{ $cartItem->itemId }}]" value="{{ $cartItem->quantity }}"
                                       aria-label="{{ trans('shop::messages.fields.quantity') }}"
                                       required @if(! $cartItem->hasQuantity()) readonly @endif>

                                <span class="k-cart-total">{{ shop_format_amount($cartItem->total()) }}</span>

                                <a href="{{ route('shop.cart.remove', $cartItem->id) }}" class="k-btn k-btn-red k-btn-sm" title="{{ trans('messages.actions.delete') }}" aria-label="{{ trans('messages.actions.delete') }}">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            </div>
                        @endforeach
                    </form>

                    <div class="k-cart-foot">
                        <form method="POST" action="{{ route('shop.cart.clear') }}">
                            @csrf
                            <button type="submit" class="k-btn k-btn-cream k-btn-sm">
                                <i class="bi bi-trash3-fill"></i> {{ trans('shop::messages.cart.clear') }}
                            </button>
                        </form>

                        <button type="submit" form="cartUpdateForm" class="k-btn k-btn-wood k-btn-sm">
                            <i class="bi bi-arrow-clockwise"></i> {{ trans('messages.actions.update') }}
                        </button>
                    </div>
                @else
                    <p class="k-empty">
                        <i class="bi bi-basket3"></i>
                        {{ trans('shop::messages.cart.empty') }}
                    </p>
                @endif
            </div>

            <a href="{{ route('shop.home') }}" class="k-btn k-btn-cream">
                <i class="bi bi-arrow-left"></i> {{ trans('shop::messages.cart.back') }}
            </a>
        </div>

        <div class="col-lg-4">
            <aside class="k-panel k-wood k-summary">
                <h2><i class="bi bi-ticket-perforated-fill"></i> {{ trans('shop::messages.coupons.add') }}</h2>
                <form action="{{ route('shop.cart.coupons.add') }}" method="POST">
                    @csrf
                    <div class="input-group @error('coupon') has-validation @enderror">
                        <input type="text" class="form-control @error('coupon') is-invalid @enderror" id="coupon" name="coupon"
                               value="{{ old('coupon') }}" placeholder="{{ trans('shop::messages.fields.code') }}"
                               aria-label="{{ trans('shop::messages.coupons.add') }}" required>
                        <button type="submit" class="btn btn-primary" aria-label="{{ trans('messages.actions.add') }}">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                        @error('coupon')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </form>

                @if(! $cart->coupons()->isEmpty())
                    <ul class="k-codes">
                        @foreach($cart->coupons() as $coupon)
                            <li>
                                <i class="bi bi-ticket-fill"></i> {{ $coupon->code }}
                                <span>-{{ $coupon->is_fixed ? shop_format_amount($coupon->discount) : $coupon->discount.' %' }}</span>
                                <form action="{{ route('shop.cart.coupons.remove', $coupon) }}" method="POST">
                                    @csrf
                                    <button type="submit" title="{{ trans('messages.actions.delete') }}" aria-label="{{ trans('messages.actions.delete') }}"><i class="bi bi-x-lg"></i></button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if(Route::has('shop.cart.giftcards.add'))
                    <h2 class="mt-4"><i class="bi bi-gift-fill"></i> {{ trans('shop::messages.giftcards.add') }}</h2>
                    <form action="{{ route('shop.cart.giftcards.add') }}" method="POST">
                        @csrf
                        <div class="input-group @error('giftcard') has-validation @enderror">
                            <input type="text" class="form-control @error('giftcard') is-invalid @enderror" id="giftcard" name="giftcard"
                                   value="{{ old('giftcard') }}" placeholder="{{ trans('shop::messages.fields.code') }}"
                                   aria-label="{{ trans('shop::messages.giftcards.add') }}" required>
                            <button type="submit" class="btn btn-primary" aria-label="{{ trans('messages.actions.add') }}">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                            @error('giftcard')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </form>

                    @if(! $cart->giftcards()->isEmpty())
                        <ul class="k-codes">
                            @foreach($cart->giftcards() as $giftcard)
                                <li>
                                    <i class="bi bi-gift"></i> {{ $giftcard->code }}
                                    <span>{{ shop_format_amount($giftcard->balance) }}</span>
                                    <form action="{{ route('shop.cart.giftcards.remove', $giftcard) }}" method="POST">
                                        @csrf
                                        <button type="submit" title="{{ trans('messages.actions.delete') }}" aria-label="{{ trans('messages.actions.delete') }}"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif

                <div class="k-sum-sep"></div>

                <div class="k-sum-line">
                    <span>{{ trans('shop::messages.fields.total') }}</span>
                    <span>{{ shop_format_amount($cart->total()) }}</span>
                </div>
                @if(Route::has('shop.cart.giftcards.add') && ! $cart->giftcards()->isEmpty())
                    <div class="k-sum-line is-big">
                        <span>À payer</span>
                        <span class="k-money">{{ shop_format_amount($cart->payableTotal()) }}</span>
                    </div>
                @endif

                <form class="mt-3 d-grid gap-3" @if(! use_site_money()) action="{{ route('shop.payments.payment') }}" @endif>
                    @if(! use_site_money())
                        @if($emailRequired ?? false)
                            <div>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                       value="{{ old('email') }}" placeholder="{{ trans('auth.email') }}" aria-label="{{ trans('auth.email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        @endif

                        @include('shop::cart._terms', ['terms' => $terms ?? null])
                    @endif

                    @if(use_site_money())
                        <button type="button" class="k-btn k-btn-leaf k-btn-lg k-btn-block" data-bs-toggle="modal" data-bs-target="#confirmBuyModal" @disabled($cart->isEmpty())>
                            <i class="bi bi-bag-check-fill"></i> {{ trans('shop::messages.buy') }}
                        </button>
                    @else
                        <button type="submit" class="k-btn k-btn-leaf k-btn-lg k-btn-block" @disabled($cart->isEmpty())>
                            <i class="bi bi-credit-card-fill"></i> {{ trans('shop::messages.cart.checkout') }}
                        </button>
                    @endif
                </form>
            </aside>
        </div>
    </div>

    @if(use_site_money())
        <div class="modal fade k-modal" id="confirmBuyModal" tabindex="-1" role="dialog" aria-labelledby="confirmBuyLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="confirmBuyLabel">{{ trans('shop::messages.cart.confirm.title') }}</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ trans('messages.actions.close') }}"></button>
                    </div>

                    <div class="modal-body fw-semibold">
                        {{ trans('shop::messages.cart.confirm.price', ['price' => shop_format_amount($cart->payableTotal())]) }}
                    </div>

                    <form class="modal-footer" method="POST" action="{{ route('shop.cart.payment') }}">
                        @csrf

                        @include('shop::cart._terms', ['terms' => $terms ?? null])

                        <div class="ms-auto d-flex gap-2">
                            <button class="k-btn k-btn-cream" type="button" data-bs-dismiss="modal">{{ trans('messages.actions.cancel') }}</button>
                            <button class="k-btn k-btn-leaf" type="submit"><i class="bi bi-check-lg"></i> {{ trans('shop::messages.cart.pay') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
