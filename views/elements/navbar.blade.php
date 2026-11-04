<nav class="k-nav navbar navbar-expand-lg" id="koriaNav">
    <div class="k-nav-inner">
        <a class="k-brand" href="{{ route('home') }}" aria-label="{{ site_name() }}">
            <img src="{{ $koriaLogo }}" alt="{{ site_name() }}">
        </a>

        <button class="navbar-toggler k-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar"
                aria-controls="navbar" aria-expanded="false" aria-label="{{ trans('messages.nav.toggle') }}">
            <i class="bi bi-list"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbar">
            @php($koriaShopButton = $k->on('nav_shop_button') ? $k->shop() : null)
            <ul class="navbar-nav k-links me-auto">
                @foreach($navbar as $element)
                    {{-- Le lien de la boutique est déjà affiché en bouton à droite --}}
                    @continue($koriaShopButton && ! $element->isDropdown() && rtrim($element->getLink(), '/') === rtrim($koriaShopButton, '/'))
                    @if(!$element->isDropdown())
                        <li class="nav-item">
                            <a class="nav-link @if($element->isCurrent()) active @endif" href="{{ $element->getLink() }}"
                               @if($element->new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                {{ $element->name }}
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle @if($element->isCurrent()) active @endif" href="#"
                               id="navbarDropdown{{ $element->id }}" role="button" data-bs-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                {{ $element->name }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown{{ $element->id }}">
                                @foreach($element->elements as $childElement)
                                    <a class="dropdown-item @if($childElement->isCurrent()) active @endif"
                                       href="{{ $childElement->getLink() }}"
                                       @if($childElement->new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                        {{ $childElement->name }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>

            <ul class="navbar-nav k-actions align-items-lg-center">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> {{ trans('auth.login') }}
                        </a>
                    </li>
                @else
                    @include('elements.notifications')

                    <li class="nav-item dropdown">
                        <a id="userDropdown" class="nav-link dropdown-toggle k-user" href="#" role="button"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <img src="{{ Auth::user()->getAvatar(32) }}" alt="" class="k-avatar">
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="bi bi-person"></i> {{ trans('messages.nav.profile') }}
                            </a>

                            @foreach(plugins()->getUserNavItems() ?? [] as $navId => $navItem)
                                <a class="dropdown-item" href="{{ route($navItem['route']) }}">
                                    <i class="{{ $navItem['icon'] ?? 'bi bi-three-dots' }}"></i> {{ $navItem['name'] }}
                                </a>
                            @endforeach

                            @if(Auth::user()->hasAdminAccess())
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> {{ trans('messages.nav.admin') }}
                                </a>
                            @endif

                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> {{ trans('auth.logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest

                @if($k->get('discord') && $k->on('nav_show_discord'))
                    <li class="nav-item">
                        <a href="{{ $k->get('discord') }}" class="k-btn k-btn-discord k-btn-square" target="_blank"
                           rel="noopener noreferrer" title="Discord" aria-label="Discord">
                            <i class="bi bi-discord"></i>
                        </a>
                    </li>
                @endif

                @if($koriaShopButton)
                    <li class="nav-item">
                        <a href="{{ $koriaShopButton }}" class="k-btn k-btn-cream"><i class="bi bi-cart3"></i> {{ $k->get('nav_shop_label') }}</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
