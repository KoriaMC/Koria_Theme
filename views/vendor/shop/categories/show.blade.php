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
    <h1>{{ $category->name }}</h1>

    <div class="row g-4" id="shop">
        <div class="col-lg-3">
            @include('shop::categories._sidebar')
        </div>

        <div class="col-lg-9">
            @if($category->description)
                <div class="ks-intro">
                    {!! $category->description !!}
                </div>
            @endif

            @if(count($category->packages))
                <div class="ks-grid">
                    @foreach($category->packages as $package)
                        @php($isAvailable = $package->purchaseStatus()->isAvailable())

                        <a href="#" class="ks-card @unless($isAvailable) is-unavailable @endunless"
                           data-package-url="{{ route('shop.packages.show', $package) }}" title="{{ $package->name }}">
                            <h2 class="ks-card-title">{{ $package->name }}</h2>

                            @if($isAvailable && $package->isDiscounted() && $package->getOriginalPrice() > 0)
                                <span class="ks-badge">-{{ (int) round(100 - $package->getPrice() / $package->getOriginalPrice() * 100) }}%</span>
                            @endif

                            <div class="ks-card-media">
                                @if($package->hasImage())
                                    <img src="{{ $package->imageUrl() }}" alt="{{ $package->name }}" loading="lazy">
                                @else
                                    <i class="bi bi-box ks-box" aria-hidden="true"></i>
                                @endif
                            </div>

                            <div class="ks-card-foot">
                                <span class="ks-price">
                                    @if(! $isAvailable)
                                        <span class="ks-off">{{ trans('shop::messages.packages.status_unavailable') }}</span>
                                    @elseif(use_site_money())
                                        {{ (float) $package->getPrice() }}
                                        <img src="{{ theme_asset('img/coin.png') }}" alt="{{ money_name($package->getPrice()) }}">
                                    @else
                                        {{ shop_format_amount($package->getPrice()) }}
                                    @endif
                                </span>
                                <span class="ks-cart" aria-hidden="true"><i class="bi bi-cart-fill"></i></span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="ks-empty">
                    <i class="bi bi-box"></i> {{ trans('shop::messages.categories.empty') }}
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true"></div>
@endsection
