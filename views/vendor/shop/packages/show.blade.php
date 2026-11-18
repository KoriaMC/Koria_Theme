@use('Azuriom\Plugin\Shop\Enums\PurchaseStatus')

<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="itemModalLabel">{{ $package->name }}</h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ trans('messages.actions.close') }}"></button>
        </div>

        <div class="modal-body">
            <div class="row g-4 align-items-start">
                @if($package->hasImage())
                    <div class="col-md-4">
                        <div class="k-crate-img rounded-4">
                            <img src="{{ $package->imageUrl() }}" alt="{{ $package->name }}">
                        </div>
                    </div>
                @endif
                <div class="{{ $package->hasImage() ? 'col-md-8' : 'col-12' }} k-prose">
                    {!! $package->description !!}
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <span class="me-auto k-crate-price mb-0">
                @if($package->isDiscounted())
                    <del>{{ shop_format_amount($package->getOriginalPrice()) }}</del>
                @endif
                <span class="k-money fs-5">
                    <i class="bi {{ use_site_money() ? 'bi-coin' : 'bi-tag-fill' }}"></i>
                    {{ shop_format_amount($package->getPrice()) }}
                </span>
            </span>

            @if($shopUser !== null)
                @php($purchaseStatus = $package->purchaseStatus())

                @if($purchaseStatus === PurchaseStatus::Subscribed)
                    <a href="{{ route('shop.profile') }}" class="k-btn k-btn-wood">
                        <i class="bi bi-gear-fill"></i> {{ trans('shop::messages.actions.manage') }}
                    </a>
                @elseif($package->isSubscription() && $purchaseStatus->isAvailable())
                    <form action="{{ route('shop.subscriptions.select', $package) }}" method="POST">
                        @csrf
                        <button type="submit" class="k-btn k-btn-leaf">
                            <i class="bi bi-arrow-repeat"></i> {{ trans('shop::messages.actions.subscribe') }}
                        </button>
                    </form>
                @elseif($package->isInCart())
                    <form action="{{ route('shop.cart.remove', $package) }}" method="POST">
                        @csrf
                        <button type="submit" class="k-btn k-btn-red">
                            <i class="bi bi-cart-x-fill"></i> {{ trans('messages.actions.remove') }}
                        </button>
                    </form>
                @elseif(! $purchaseStatus->isAvailable())
                    <span class="k-crate-status">{{ $purchaseStatus->message() }}</span>
                @else
                    <form action="{{ route('shop.packages.buy', $package) }}" method="POST" class="d-flex flex-wrap align-items-center gap-3">
                        @csrf

                        @if($package->custom_price)
                            <label class="k-qty" for="price">
                                {{ trans('shop::messages.fields.price') }}
                                <input type="number" step="0.01" min="{{ $package->getPrice() }}" class="form-control" name="price" id="price" value="{{ $package->getPrice() }}">
                            </label>
                        @endif

                        @if($package->has_quantity)
                            <label class="k-qty" for="quantity">
                                {{ trans('shop::messages.fields.quantity') }}
                                <input type="number" min="1" max="{{ $package->getMaxQuantity() }}" class="form-control" name="quantity" id="quantity" value="1" required>
                            </label>
                        @endif

                        <button type="submit" class="k-btn k-btn-leaf">
                            <i class="bi bi-cart-plus-fill"></i> {{ trans('shop::messages.buy') }}
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('shop.login') }}" class="k-btn k-btn-leaf">
                    <i class="bi bi-box-arrow-in-right"></i> {{ trans('shop::messages.buy') }}
                </a>
            @endif
        </div>
    </div>
</div>
