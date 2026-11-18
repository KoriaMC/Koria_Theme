@extends('layouts.app')

@section('title', trans('shop::messages.title'))

@section('content')
    @include('elements.page-head', [
        'title' => trans('shop::messages.title'),
        'icon' => 'bi-cart3',
        'subtitle' => 'Grades, kits et bonus pour équiper ta faction. Chaque achat aide le serveur à vivre !',
    ])

    <div class="row g-4" id="shop">
        <div class="col-lg-3">
            @include('shop::categories._sidebar')
        </div>

        <div class="col-lg-9">
            <div class="k-box">
                <div class="k-box-body k-prose">
                    {{ $welcome }}
                </div>
            </div>

            @if(count($categories))
                <div class="k-shop-tiles">
                    @foreach($categories as $tileCategory)
                        <a href="{{ route('shop.categories.show', $tileCategory) }}" class="k-shop-tile">
                            <span class="k-head-hex" aria-hidden="true">
                                <svg viewBox="0 0 120 128">
                                    <polygon class="k-hex-frame" points="60,4 112,33 112,95 60,124 8,95 8,33"/>
                                    <polygon class="k-hex-inner" points="60,18 99,40 99,88 60,110 21,88 21,40"/>
                                    <path class="k-hex-shine" d="M21 40 L60 18 L99 40 L99 56 Q60 44 21 56 Z"/>
                                </svg>
                                <i class="{{ $tileCategory->icon ?: 'bi bi-box-seam' }}"></i>
                            </span>
                            {{ $tileCategory->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
