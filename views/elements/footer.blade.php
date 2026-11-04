<footer class="k-footer mt-auto">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-5">
                <a class="k-brand" href="{{ route('home') }}" aria-label="{{ site_name() }}">
                    <img src="{{ $koriaLogo }}" alt="{{ site_name() }}">
                </a>
                <p class="k-footer-copy mt-3 mb-1">
                    {{ str_replace('{year}', date('Y'), setting('copyright')) }}
                </p>
                @if($k->get('footer_text'))
                    <p class="k-footer-small mb-0">{{ $k->get('footer_text') }}</p>
                @endif
            </div>

            <div class="col-6 col-lg-3">
                <h3 class="k-footer-title">{{ $k->get('footer_links_title') }}</h3>
                <ul class="k-footer-links">
                    <li><a href="{{ route('home') }}">Accueil</a></li>
                    <li><a href="{{ $k->launcher() ?: route('home').'#rejoindre' }}">Launcher</a></li>
                    <li><a href="{{ route('posts.index') }}">Actualités</a></li>
                    @if($k->vote())
                        <li><a href="{{ $k->vote() }}">Vote</a></li>
                    @endif
                    @if($k->shop())
                        <li><a href="{{ $k->shop() }}">{{ $k->get('nav_shop_label') }}</a></li>
                    @endif
                    @guest
                        <li><a href="{{ route('login') }}">{{ trans('auth.login') }}</a></li>
                    @else
                        <li><a href="{{ route('profile.index') }}">{{ trans('messages.nav.profile') }}</a></li>
                    @endguest
                </ul>
            </div>

            <div class="col-6 col-lg-4">
                <h3 class="k-footer-title">{{ $k->get('footer_social_title') }}</h3>
                <ul class="k-footer-links">
                    @if(! $k->get('discord') && count(social_links()) === 0)
                        <li class="opacity-75">Bientôt disponible</li>
                    @endif
                    @if($k->get('discord'))
                        <li><a href="{{ $k->get('discord') }}" target="_blank" rel="noopener noreferrer"><i class="bi bi-discord"></i> Discord</a></li>
                    @endif
                    @foreach(social_links() as $link)
                        <li>
                            <a href="{{ $link->value }}" target="_blank" rel="noopener noreferrer">
                                <i class="{{ $link->icon }}"></i> {{ $link->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</footer>
