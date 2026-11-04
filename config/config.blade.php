@extends('admin.layouts.admin')

@section('title', 'Thème KoriaMC')

@php
    $k = app()->bound('koria.theme') ? app('koria.theme') : app()->instance('koria.theme', require resource_path('themes/koria/config/helpers.php'));
    $D = $k::DEFAULTS;
    $fonts = array_combine(array_keys($k::FONTS), array_keys($k::FONTS));
    $fontUrls = [];
    foreach ($k::FONTS as $fontName => $fontWeights) {
        $fontUrls[$fontName] = 'https://fonts.googleapis.com/css2?family='.str_replace(' ', '+', $fontName).':wght@'.$fontWeights.'&display=swap';
    }
    $defaultLogo = setting('logo') ? image_url(setting('logo')) : theme_asset('img/logo.webp');
    $tabs = [
        'general' => ['bi-palette-fill', 'Général'],
        'hero' => ['bi-stars', 'En-tête'],
        'links' => ['bi-link-45deg', 'Launcher & liens'],
        'home' => ['bi-layout-text-window-reverse', 'Sections de l\'accueil'],
        'footer' => ['bi-layout-sidebar-inset-reverse', 'Pied de page'],
    ];
@endphp

@section('content')
    <form action="{{ route('admin.themes.config', $theme) }}" method="POST" class="k-config" id="koriaConfigForm">
        @csrf

        {{-- En-tête de la page --}}
        <div class="k-config-hero mb-4">
            <img src="{{ $defaultLogo }}" alt="KoriaMC" class="k-config-logo">
            <div class="k-config-hero-text">
                <span class="k-config-tag"><i class="bi bi-brush"></i> Thème Koria · v1.0</span>
                <p class="mb-0">Tout le site se règle ici : couleurs, police, textes, images et sections. Astuce : dans les titres, entoure un mot d'<code>*astérisques*</code> pour le mettre en bleu.</p>
            </div>
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-secondary k-config-visit">
                <i class="bi bi-box-arrow-up-right"></i> Voir le site
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Certains champs sont incorrects, vérifie les onglets marqués en rouge.
            </div>
        @endif

        <div class="row g-4">
            <div class="col-xxl-7 col-xl-7">
                <ul class="nav k-tabs mb-3" role="tablist">
                    @foreach($tabs as $tabId => [$tabIcon, $tabLabel])
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($loop->first) active @endif" id="tab-{{ $tabId }}-btn" data-bs-toggle="pill"
                                    data-bs-target="#tab-{{ $tabId }}" type="button" role="tab" data-tab="{{ $tabId }}">
                                <i class="bi {{ $tabIcon }}"></i> <span>{{ $tabLabel }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    {{-- ========== Général ========== --}}
                    <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                        <div class="card mb-4">
                            <div class="card-header"><h2 class="h5"><i class="bi bi-droplet-half me-2"></i>Couleurs</h2></div>
                            <div class="card-body">
                                <p class="k-config-help">Choisis 4 couleurs de base : toutes les nuances (clair, foncé, bordures) sont calculées automatiquement.</p>
                                <div class="row g-3">
                                    @include('admin.koria.field', ['key' => 'color_leaf', 'label' => 'Végétation (boutons, titres)', 'type' => 'color', 'default' => $D['color_leaf'], 'col' => 'col-md-6'])
                                    @include('admin.koria.field', ['key' => 'color_wood', 'label' => 'Bois (panneaux, navigation)', 'type' => 'color', 'default' => $D['color_wood'], 'col' => 'col-md-6'])
                                    @include('admin.koria.field', ['key' => 'color_water', 'label' => 'Eau (mots en accent, océan)', 'type' => 'color', 'default' => $D['color_water'], 'col' => 'col-md-6'])
                                    @include('admin.koria.field', ['key' => 'color_bg', 'label' => 'Fond des pages', 'type' => 'color', 'default' => $D['color_bg'], 'col' => 'col-md-6'])
                                </div>
                                <div class="k-presets mt-3">
                                    <span class="form-label mb-0 me-1">Ambiances :</span>
                                    @foreach([
                                        'Jungle' => ['#8bd630', '#b8763b', '#3cc8f4', '#e5f1c6'],
                                        'Volcan' => ['#f0642e', '#7a4b3a', '#ffc23d', '#f6e3d4'],
                                        'Glace' => ['#4fc3f7', '#8d7b6a', '#7c4dff', '#e4f1fa'],
                                        'Nuit' => ['#9b6bff', '#5a4636', '#38e1c8', '#e7e2f5'],
                                        'Désert' => ['#e8b13a', '#a3683a', '#2fb7b0', '#f6ead0'],
                                    ] as $presetName => $presetColors)
                                        <button type="button" class="k-preset" data-preset='@json($presetColors)' title="{{ $presetName }}">
                                            @foreach($presetColors as $presetColor)<span style="background: {{ $presetColor }}"></span>@endforeach
                                            {{ $presetName }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header"><h2 class="h5"><i class="bi bi-fonts me-2"></i>Style</h2></div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @include('admin.koria.field', ['key' => 'font', 'label' => 'Police d\'écriture', 'type' => 'select', 'options' => $fonts, 'col' => 'col-md-6'])
                                    <div class="col-md-6">
                                        <span class="form-label d-block">Aperçu de la police</span>
                                        <div class="k-font-sample" data-font-sample>KORIAMC · Faction 123</div>
                                    </div>
                                    @include('admin.koria.field', ['key' => 'animations', 'label' => 'Animations (flottement, apparition au défilement, vagues)', 'type' => 'switch'])
                                    @include('admin.koria.field', ['key' => 'admin_skin', 'label' => 'Habiller aussi le panel admin aux couleurs du thème', 'type' => 'switch', 'help' => 'Désactive-le pour retrouver l\'admin bleu d\'origine.'])
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ========== En-tête ========== --}}
                    <div class="tab-pane fade" id="tab-hero" role="tabpanel">
                        <div class="card mb-4">
                            <div class="card-header"><h2 class="h5"><i class="bi bi-image me-2"></i>Images</h2></div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @include('admin.koria.field', ['key' => 'hero_logo', 'label' => 'Logo (accueil, navigation, pied de page)', 'type' => 'image', 'defaultSrc' => $defaultLogo, 'emptyLabel' => 'Logo KoriaMC du thème', 'col' => 'col-md-6'])
                                    @include('admin.koria.field', ['key' => 'hero_background', 'label' => 'Image de fond de l\'accueil', 'type' => 'image', 'emptyLabel' => 'Ciel de jungle (par défaut)', 'col' => 'col-md-6'])
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header"><h2 class="h5"><i class="bi bi-type me-2"></i>Contenu</h2></div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @include('admin.koria.field', ['key' => 'hero_sign_enabled', 'label' => 'Afficher le panneau sous le logo', 'type' => 'switch'])
                                    @include('admin.koria.field', ['key' => 'hero_sign', 'label' => 'Texte du panneau', 'max' => 60, 'icon' => 'bi-signpost-2', 'col' => 'col-md-6'])
                                    @include('admin.koria.field', ['key' => 'hero_button', 'label' => 'Texte du gros bouton', 'max' => 60, 'icon' => 'bi-play-fill', 'col' => 'col-md-6'])
                                    @include('admin.koria.field', ['key' => 'hero_subtitle', 'label' => 'Phrase d\'accroche', 'type' => 'textarea', 'rows' => 2, 'max' => 250])
                                    @include('admin.koria.field', ['key' => 'hero_show_players', 'label' => 'Afficher le nombre de joueurs en ligne (si un serveur est relié)', 'type' => 'switch'])
                                    @include('admin.koria.field', ['key' => 'hero_canopy', 'label' => 'Feuillage dans les coins', 'type' => 'switch'])
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ========== Launcher & liens ========== --}}
                    <div class="tab-pane fade" id="tab-links" role="tabpanel">
                        <div class="card mb-4">
                            <div class="card-header"><h2 class="h5"><i class="bi bi-download me-2"></i>Launcher</h2></div>
                            <div class="card-body">
                                <p class="k-config-help">Ces liens alimentent tous les boutons de téléchargement du site.</p>
                                <div class="row g-3">
                                    @include('admin.koria.field', ['key' => 'launcher_url', 'label' => 'Windows (lien principal)', 'icon' => 'bi-windows', 'placeholder' => 'https://koriamc.fr/launcher/KoriaMC-Setup.exe'])
                                    @include('admin.koria.field', ['key' => 'launcher_mac_url', 'label' => 'macOS (optionnel)', 'icon' => 'bi-apple', 'placeholder' => 'https://…', 'col' => 'col-md-6'])
                                    @include('admin.koria.field', ['key' => 'launcher_linux_url', 'label' => 'Linux (optionnel)', 'icon' => 'bi-ubuntu', 'placeholder' => 'https://…', 'col' => 'col-md-6'])
                                </div>
                                <div class="k-config-status mt-3" data-launcher-status><i class="bi"></i> <span></span></div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header"><h2 class="h5"><i class="bi bi-share me-2"></i>Discord & boutique</h2></div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @include('admin.koria.field', ['key' => 'discord', 'label' => 'Invitation Discord', 'type' => 'url', 'icon' => 'bi-discord', 'placeholder' => 'https://discord.gg/…', 'help' => 'Vide = aucun bouton ni section Discord.'])
                                    @include('admin.koria.field', ['key' => 'nav_show_discord', 'label' => 'Bouton Discord dans la barre de navigation', 'type' => 'switch'])
                                    @include('admin.koria.field', ['key' => 'nav_shop_button', 'label' => 'Bouton Boutique mis en avant à droite de la navigation', 'type' => 'switch', 'help' => 'Activé : le lien Boutique du menu est remplacé par ce bouton. Désactivé : le lien normal du menu reste affiché.'])
                                    @include('admin.koria.field', ['key' => 'shop_url', 'label' => 'Lien de la boutique', 'icon' => 'bi-cart3', 'placeholder' => 'https://… ou /shop', 'col' => 'col-md-8', 'help' => Route::has('shop.home') ? 'Vide = plugin Boutique installé.' : 'Vide = pas de bouton Boutique.'])
                                    @include('admin.koria.field', ['key' => 'nav_shop_label', 'label' => 'Texte du bouton', 'max' => 40, 'col' => 'col-md-4'])
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ========== Sections de l'accueil ========== --}}
                    <div class="tab-pane fade" id="tab-home" role="tabpanel">
                        <div class="accordion k-accordion" id="homeSections">
                            {{-- Comment rejoindre --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#sec-join">
                                        <i class="bi bi-signpost-split-fill me-2"></i> Comment rejoindre
                                        <span class="k-section-state" data-state-for="join_enabled"></span>
                                    </button>
                                </h2>
                                <div id="sec-join" class="accordion-collapse collapse show" data-bs-parent="#homeSections">
                                    <div class="accordion-body row g-3">
                                        @include('admin.koria.field', ['key' => 'join_enabled', 'label' => 'Afficher cette section', 'type' => 'switch'])
                                        @include('admin.koria.field', ['key' => 'about_title', 'label' => 'Titre', 'max' => 120])
                                        @include('admin.koria.field', ['key' => 'about_text', 'label' => 'Texte', 'type' => 'textarea', 'max' => 1000])
                                        @include('admin.koria.field', ['key' => 'join_card_title', 'label' => 'Titre du panneau en bois', 'max' => 60])
                                        @for($i = 0; $i < 3; $i++)
                                            <div class="col-12"><div class="k-subcard">
                                                <span class="k-subcard-num">{{ $i + 1 }}</span>
                                                <div class="row g-2 flex-grow-1">
                                                    @include('admin.koria.field', ['key' => "steps.$i.title", 'label' => 'Étape '.($i + 1).' : titre', 'max' => 60, 'col' => 'col-md-5'])
                                                    @include('admin.koria.field', ['key' => "steps.$i.text", 'label' => 'Description', 'max' => 200, 'col' => 'col-md-7'])
                                                </div>
                                            </div></div>
                                        @endfor
                                        @include('admin.koria.field', ['key' => 'launcher_image_enabled', 'label' => 'Afficher l\'image du launcher à côté', 'type' => 'switch'])
                                        @include('admin.koria.field', ['key' => 'launcher_image', 'label' => 'Image du launcher', 'type' => 'image', 'defaultSrc' => theme_asset('img/launcher-preview.webp'), 'emptyLabel' => 'Aperçu du launcher KoriaMC', 'col' => 'col-md-7'])
                                        @include('admin.koria.field', ['key' => 'launcher_caption', 'label' => 'Légende de l\'image', 'max' => 60, 'col' => 'col-md-5'])
                                    </div>
                                </div>
                            </div>

                            {{-- Serveurs --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-servers">
                                        <i class="bi bi-hdd-network-fill me-2"></i> Serveurs
                                        <span class="k-section-state" data-state-for="servers_enabled"></span>
                                    </button>
                                </h2>
                                <div id="sec-servers" class="accordion-collapse collapse" data-bs-parent="#homeSections">
                                    <div class="accordion-body row g-3">
                                        @include('admin.koria.field', ['key' => 'servers_enabled', 'label' => 'Afficher les serveurs marqués « afficher sur l\'accueil »', 'type' => 'switch'])
                                        @include('admin.koria.field', ['key' => 'servers_title', 'label' => 'Titre', 'max' => 120])
                                    </div>
                                </div>
                            </div>

                            {{-- Points forts --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-features">
                                        <i class="bi bi-hexagon-fill me-2"></i> Points forts
                                        <span class="k-section-state" data-state-for="features_enabled"></span>
                                    </button>
                                </h2>
                                <div id="sec-features" class="accordion-collapse collapse" data-bs-parent="#homeSections">
                                    <div class="accordion-body row g-3">
                                        @include('admin.koria.field', ['key' => 'features_enabled', 'label' => 'Afficher cette section', 'type' => 'switch'])
                                        @include('admin.koria.field', ['key' => 'features_title', 'label' => 'Titre', 'max' => 120])
                                        <div class="col-12 form-text mt-0">Icônes : choisis un nom sur <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener">icons.getbootstrap.com</a> et préfixe-le par <code>bi-</code> (ex. <code>bi-fire</code>). Laisse un titre vide pour masquer la case.</div>
                                        @for($i = 0; $i < 4; $i++)
                                            <div class="col-md-6"><div class="k-subcard k-subcard-col">
                                                <span class="k-subcard-num">{{ $i + 1 }}</span>
                                                <div class="row g-2">
                                                    @include('admin.koria.field', ['key' => "features.$i.icon", 'label' => 'Icône', 'type' => 'icon', 'col' => 'col-sm-5'])
                                                    @include('admin.koria.field', ['key' => "features.$i.title", 'label' => 'Titre', 'max' => 40, 'col' => 'col-sm-7'])
                                                    @include('admin.koria.field', ['key' => "features.$i.text", 'label' => 'Texte', 'type' => 'textarea', 'rows' => 2, 'max' => 200])
                                                </div>
                                            </div></div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            {{-- Actualités --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-news">
                                        <i class="bi bi-newspaper me-2"></i> Actualités
                                        <span class="k-section-state" data-state-for="news_enabled"></span>
                                    </button>
                                </h2>
                                <div id="sec-news" class="accordion-collapse collapse" data-bs-parent="#homeSections">
                                    <div class="accordion-body row g-3">
                                        @include('admin.koria.field', ['key' => 'news_enabled', 'label' => 'Afficher cette section', 'type' => 'switch'])
                                        @include('admin.koria.field', ['key' => 'news_title', 'label' => 'Titre', 'max' => 120, 'col' => 'col-md-8'])
                                        @include('admin.koria.field', ['key' => 'news_count', 'label' => 'Nombre d\'articles', 'type' => 'select', 'options' => [1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6'], 'col' => 'col-md-4'])
                                        @include('admin.koria.field', ['key' => 'news_empty', 'label' => 'Message quand il n\'y a aucun article', 'max' => 200])
                                    </div>
                                </div>
                            </div>

                            {{-- Discord --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-discord">
                                        <i class="bi bi-discord me-2"></i> Discord
                                        <span class="k-section-state" data-state-for="discord_enabled"></span>
                                    </button>
                                </h2>
                                <div id="sec-discord" class="accordion-collapse collapse" data-bs-parent="#homeSections">
                                    <div class="accordion-body row g-3">
                                        @include('admin.koria.field', ['key' => 'discord_enabled', 'label' => 'Afficher cette section (il faut aussi un lien Discord)', 'type' => 'switch'])
                                        @include('admin.koria.field', ['key' => 'discord_title', 'label' => 'Titre', 'max' => 120, 'col' => 'col-md-7'])
                                        @include('admin.koria.field', ['key' => 'discord_button', 'label' => 'Texte du bouton', 'max' => 60, 'col' => 'col-md-5'])
                                        @include('admin.koria.field', ['key' => 'discord_text', 'label' => 'Texte', 'type' => 'textarea', 'rows' => 2, 'max' => 400])
                                    </div>
                                </div>
                            </div>

                            {{-- Appel final --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec-cta">
                                        <i class="bi bi-megaphone-fill me-2"></i> Appel final
                                        <span class="k-section-state" data-state-for="cta_enabled"></span>
                                    </button>
                                </h2>
                                <div id="sec-cta" class="accordion-collapse collapse" data-bs-parent="#homeSections">
                                    <div class="accordion-body row g-3">
                                        @include('admin.koria.field', ['key' => 'cta_enabled', 'label' => 'Afficher cette section', 'type' => 'switch'])
                                        @include('admin.koria.field', ['key' => 'cta_title', 'label' => 'Titre', 'max' => 120, 'col' => 'col-md-7'])
                                        @include('admin.koria.field', ['key' => 'cta_button', 'label' => 'Texte du bouton', 'max' => 60, 'col' => 'col-md-5'])
                                        @include('admin.koria.field', ['key' => 'cta_text', 'label' => 'Texte', 'type' => 'textarea', 'rows' => 2, 'max' => 400])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ========== Pied de page ========== --}}
                    <div class="tab-pane fade" id="tab-footer" role="tabpanel">
                        <div class="card mb-4">
                            <div class="card-header"><h2 class="h5"><i class="bi bi-layout-sidebar-inset-reverse me-2"></i>Pied de page</h2></div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @include('admin.koria.field', ['key' => 'footer_text', 'label' => 'Petit texte sous le copyright', 'max' => 300, 'help' => 'Le copyright lui-même se règle dans Paramètres → Général.'])
                                    @include('admin.koria.field', ['key' => 'footer_links_title', 'label' => 'Titre de la colonne de liens', 'max' => 40, 'col' => 'col-md-6'])
                                    @include('admin.koria.field', ['key' => 'footer_social_title', 'label' => 'Titre de la colonne réseaux', 'max' => 40, 'col' => 'col-md-6'])
                                </div>
                                <p class="k-config-help mt-3 mb-0"><i class="bi bi-info-circle"></i> Les réseaux sociaux affichés viennent de <a href="{{ route('admin.social-links.index') }}">Paramètres → Réseaux sociaux</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== Aperçu en direct ========== --}}
            <div class="col-xxl-5 col-xl-5">
                <div class="k-config-sticky">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h2 class="h5"><i class="bi bi-eye me-2"></i>Aperçu en direct</h2>
                        </div>
                        <div class="card-body p-0">
                            <div class="k-preview" id="koriaPreview">
                                <div class="k-preview-hero" data-show="hero">
                                    <div class="k-preview-canopy" data-toggle-by="hero_canopy"></div>
                                    <img src="{{ $k->get('hero_logo') ? image_url($k->get('hero_logo')) : $defaultLogo }}" alt="" class="k-preview-logo" data-preview-img="hero_logo" data-default-src="{{ $defaultLogo }}">
                                    <span class="k-preview-sign" data-toggle-by="hero_sign_enabled"><i class="bi bi-shield-fill"></i> <span data-text="hero_sign"></span></span>
                                    <p class="k-preview-subtitle" data-text="hero_subtitle"></p>
                                    <span class="k-preview-btn" data-launcher-btn><i class="bi bi-play-fill"></i> <span data-text="hero_button"></span></span>
                                </div>
                                <div class="k-preview-body" data-toggle-by="join_enabled">
                                    <h3 class="k-preview-title" data-title="about_title"></h3>
                                    <p class="k-preview-text" data-text="about_text"></p>
                                    <div class="k-preview-wood">
                                        <strong data-text="join_card_title"></strong>
                                        @for($i = 0; $i < 3; $i++)
                                            <div class="k-preview-step"><b>{{ $i + 1 }}</b> <span data-text="steps.{{ $i }}.title"></span></div>
                                        @endfor
                                    </div>
                                </div>
                                <div class="k-preview-body k-preview-features" data-toggle-by="features_enabled">
                                    <h3 class="k-preview-title text-center" data-title="features_title"></h3>
                                    <div class="k-preview-hexes">
                                        @for($i = 0; $i < 4; $i++)
                                            <div class="k-preview-hex" data-feature="{{ $i }}"><i class="bi" data-icon="features.{{ $i }}.icon"></i><small data-text="features.{{ $i }}.title"></small></div>
                                        @endfor
                                    </div>
                                </div>
                                <div class="k-preview-water" data-toggle-by="cta_enabled">
                                    <h3 class="k-preview-title" data-title="cta_title"></h3>
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <span class="k-preview-btn k-preview-btn-sm" data-launcher-btn><i class="bi bi-download"></i> <span data-text="cta_button"></span></span>
                                        <span class="k-preview-btn k-preview-btn-sm k-preview-btn-discord" data-discord-btn><i class="bi bi-discord"></i> Discord</span>
                                    </div>
                                </div>
                                <div class="k-preview-footer"><span data-text="footer_text"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Barre d'enregistrement --}}
        <div class="k-config-savebar">
            <span class="k-config-dirty"><i class="bi bi-pencil-fill"></i> Modifications non enregistrées</span>
            <a href="{{ route('admin.themes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Thèmes
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
            </button>
        </div>
    </form>
@endsection

@push('footer-scripts')
    <script>
        (function () {
            const form = document.getElementById('koriaConfigForm');
            const preview = document.getElementById('koriaPreview');
            if (!form || !preview) return;

            const defaults = @json($D);
            const fontUrls = @json($fontUrls);

            const byKey = (key) => form.querySelector('[data-preview="' + key + '"]');
            const dig = (obj, key) => key.split('.').reduce((o, p) => (o == null ? undefined : o[p]), obj);
            const val = (key) => {
                const el = byKey(key);
                if (!el) return dig(defaults, key);
                if (el.type === 'checkbox') return el.checked;
                const v = el.value.trim();
                return v === '' ? dig(defaults, key) : v;
            };
            const escape = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c]));
            const accent = (s) => escape(s).replace(/\*(.+?)\*/g, '<span class="k-accent">$1</span>');

            // Couleurs : sélecteur <-> champ texte
            form.querySelectorAll('[data-color-for]').forEach((picker) => {
                const hex = document.getElementById(picker.dataset.colorFor);
                picker.addEventListener('input', () => { hex.value = picker.value; hex.dispatchEvent(new Event('input', {bubbles: true})); });
                hex.addEventListener('input', () => { if (/^#[0-9a-f]{6}$/i.test(hex.value)) picker.value = hex.value; });
            });
            form.querySelectorAll('[data-color-reset]').forEach((btn) => btn.addEventListener('click', () => {
                const hex = document.getElementById(btn.dataset.colorReset + '_hex');
                hex.value = hex.dataset.default; hex.dispatchEvent(new Event('input', {bubbles: true}));
            }));
            form.querySelectorAll('[data-preset]').forEach((btn) => btn.addEventListener('click', () => {
                const colors = JSON.parse(btn.dataset.preset);
                ['color_leaf', 'color_wood', 'color_water', 'color_bg'].forEach((key, i) => {
                    const hex = byKey(key); hex.value = colors[i]; hex.dispatchEvent(new Event('input', {bubbles: true}));
                });
            }));

            // Images : miniature sous la liste
            form.querySelectorAll('[data-image-preview]').forEach((select) => select.addEventListener('change', () => {
                const img = document.getElementById(select.dataset.imagePreview);
                const opt = select.selectedOptions[0];
                const src = opt && opt.dataset.src ? opt.dataset.src : select.dataset.defaultSrc;
                img.hidden = !src; if (src) img.src = src;
            }));

            // Icônes : aperçu à côté du champ
            form.querySelectorAll('[data-icon-input]').forEach((input) => input.addEventListener('input', () => {
                const icon = form.querySelector('[data-icon-preview="' + input.dataset.iconInput + '"]');
                icon.className = 'bi ' + input.value.trim();
            }));

            function loadFont(font) {
                if (!fontUrls[font] || document.querySelector('link[data-font="' + font + '"]')) return;
                const link = document.createElement('link');
                link.rel = 'stylesheet'; link.href = fontUrls[font]; link.dataset.font = font;
                document.head.appendChild(link);
            }

            function render() {
                // Couleurs et police
                ['leaf', 'wood', 'water', 'bg'].forEach((c) => {
                    const v = val('color_' + c);
                    if (/^#[0-9a-f]{6}$/i.test(v)) preview.style.setProperty('--k-' + c, v);
                });
                const font = val('font'); loadFont(font);
                preview.style.setProperty('--k-font', "'" + font + "', system-ui, sans-serif");
                const sample = form.querySelector('[data-font-sample]');
                if (sample) sample.style.fontFamily = "'" + font + "', system-ui, sans-serif";

                // Textes et titres
                preview.querySelectorAll('[data-text]').forEach((el) => { const v = val(el.dataset.text); el.textContent = v ?? ''; el.hidden = !v; });
                preview.querySelectorAll('[data-title]').forEach((el) => { el.innerHTML = accent(val(el.dataset.title)); });
                preview.querySelectorAll('[data-icon]').forEach((el) => { el.className = 'bi ' + (val(el.dataset.icon) || 'bi-star-fill'); });
                preview.querySelectorAll('[data-feature]').forEach((el) => { el.hidden = !val('features.' + el.dataset.feature + '.title'); });

                // Interrupteurs
                preview.querySelectorAll('[data-toggle-by]').forEach((el) => { el.hidden = !val(el.dataset.toggleBy); });
                form.querySelectorAll('[data-state-for]').forEach((el) => {
                    const on = val(el.dataset.stateFor);
                    el.textContent = on ? 'Affichée' : 'Masquée'; el.classList.toggle('is-off', !on);
                });

                // Logo
                const logoSelect = byKey('hero_logo'); const logo = preview.querySelector('[data-preview-img="hero_logo"]');
                if (logoSelect && logo) {
                    const opt = logoSelect.selectedOptions[0];
                    logo.src = opt && opt.dataset.src ? opt.dataset.src : logo.dataset.defaultSrc;
                }

                // Liens
                const launcher = (byKey('launcher_url') || {}).value?.trim();
                const discord = (byKey('discord') || {}).value?.trim();
                preview.querySelectorAll('[data-launcher-btn]').forEach((el) => el.classList.toggle('is-disabled', !launcher));
                preview.querySelectorAll('[data-discord-btn]').forEach((el) => { el.hidden = !discord; });
                const status = form.querySelector('[data-launcher-status]');
                if (status) {
                    status.classList.toggle('is-ok', !!launcher);
                    status.querySelector('i').className = 'bi ' + (launcher ? 'bi-check-circle-fill' : 'bi-hourglass-split');
                    status.querySelector('span').textContent = launcher
                        ? 'Les boutons du site pointent vers ton launcher.'
                        : 'Aucun lien : les boutons affichent « Launcher bientôt disponible ».';
                }
            }

            // Onglet mémorisé + onglets en erreur
            try {
                const saved = localStorage.getItem('koria.config.tab');
                const btn = saved && document.getElementById('tab-' + saved + '-btn');
                if (btn) btn.click();
            } catch (e) {}
            form.querySelectorAll('[data-tab]').forEach((btn) => btn.addEventListener('shown.bs.tab', () => {
                try { localStorage.setItem('koria.config.tab', btn.dataset.tab); } catch (e) {}
            }));
            form.querySelectorAll('.tab-pane').forEach((pane) => {
                if (pane.querySelector('.is-invalid')) {
                    const btn = document.getElementById(pane.id + '-btn');
                    if (btn) btn.classList.add('has-error');
                }
            });

            // Modifications non enregistrées
            const snapshot = () => new URLSearchParams(new FormData(form)).toString();
            const initial = snapshot();
            let dirty = false;
            form.addEventListener('input', () => { render(); dirty = snapshot() !== initial; form.classList.toggle('is-dirty', dirty); });
            form.addEventListener('change', () => { render(); dirty = snapshot() !== initial; form.classList.toggle('is-dirty', dirty); });
            form.addEventListener('submit', () => { dirty = false; });
            window.addEventListener('beforeunload', (e) => { if (dirty) { e.preventDefault(); e.returnValue = ''; } });

            render();
        })();
    </script>
@endpush
