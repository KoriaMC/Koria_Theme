@extends('layouts.app')

@section('title', $category->name)

@push('footer-scripts')
    <script>
        document.querySelectorAll('[data-package-url]').forEach(function (el) {
            el.addEventListener('click', function (ev) {
                ev.preventDefault();

                axios.get(el.dataset['packageUrl']).then(function (response) {
                    const itemModal = document.getElementById('itemModal');
                    itemModal.innerHTML = response.data;
                    new bootstrap.Modal(itemModal).show();
                }).catch(function (error) {
                    createAlert('danger', error, true);
                });
            });
        });
    </script>
@endpush

@section('content')
    @include('elements.page-head', [
        'title' => $category->name,
        'icon' => \Illuminate\Support\Str::after($category->icon ?: 'bi bi-box-seam', 'bi '),
        'crumbs' => [[trans('shop::messages.title'), route('shop.home')]],
    ])

    <div class="row g-4" id="shop">
        <div class="col-lg-3">
            @include('shop::categories._sidebar')
        </div>

        <div class="col-lg-9">
            @if($category->description)
                <div class="k-box">
                    <div class="k-box-body k-prose">
                        {!! $category->description !!}
                    </div>
                </div>
            @endif

            <div class="row g-4">
                @forelse($category->packages as $package)
                    @php
                        $isAvailable = $package->purchaseStatus()->isAvailable();
                        $discount = $package->isDiscounted() && $package->getOriginalPrice() > 0
                            ? (int) round(100 - ($package->getPrice() / $package->getOriginalPrice()) * 100)
                            : 0;
                    @endphp

                    <div class="col-6 col-xl-4">
                        <article class="k-crate @unless($isAvailable) is-unavailable @endunless">
                            @if($isAvailable && $discount > 0)
                                <span class="k-crate-off">-{{ $discount }}%</span>
                            @endif

                            <a href="#" class="k-crate-img" data-package-url="{{ route('shop.packages.show', $package) }}" aria-label="{{ $package->name }}">
                                @if($package->hasImage())
                                    <img src="{{ $package->imageUrl() }}" alt="{{ $package->name }}" loading="lazy">
                                @else
                                    <i class="bi bi-box2-heart-fill k-crate-icon" aria-hidden="true"></i>
                                @endif
                            </a>

                            <div class="k-crate-body">
                                <h2 class="k-crate-name">{{ $package->name }}</h2>

                                <p class="k-crate-price">
                                    @if(! $isAvailable)
                                        <span class="k-crate-status">{{ trans('shop::messages.packages.status_unavailable') }}</span>
                                    @else
                                        @if($package->isDiscounted())
                                            <del>{{ shop_format_amount($package->getOriginalPrice()) }}</del>
                                        @endif
                                        <span class="k-money">
                                            <i class="bi {{ use_site_money() ? 'bi-coin' : 'bi-tag-fill' }}"></i>
                                            {{ shop_format_amount($package->getPrice()) }}
                                        </span>
                                    @endif
                                </p>

                                <a href="#" class="k-btn {{ $isAvailable ? 'k-btn-leaf' : 'k-btn-cream' }} k-btn-block" data-package-url="{{ route('shop.packages.show', $package) }}">
                                    @if($isAvailable)
                                        <i class="bi bi-cart-plus-fill"></i> {{ trans('shop::messages.buy') }}
                                    @else
                                        <i class="bi bi-eye-fill"></i> {{ trans('messages.actions.show') }}
                                    @endif
                                </a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="k-box">
                            <p class="k-empty">
                                <i class="bi bi-box2"></i>
                                {{ trans('shop::messages.categories.empty') }}
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="modal fade k-modal" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true"></div>
@endsection
