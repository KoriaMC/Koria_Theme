<?php

/*
 * Réglages du thème Koria : valeurs par défaut + petites aides pour les vues.
 * Chargé une seule fois par requête via app('koria.theme') (voir views/elements/koria.blade.php).
 */

return new class
{
    /** Valeurs par défaut de tous les réglages. */
    public const DEFAULTS = [
        // Général
        'color_leaf' => '#8bd630',
        'color_wood' => '#b8763b',
        'color_water' => '#3cc8f4',
        'color_bg' => '#e5f1c6',
        'font' => 'Rubik',
        'animations' => true,
        'admin_skin' => true,

        // Navigation
        'nav_shop_button' => true,
        'nav_shop_label' => 'Boutique',
        'nav_show_discord' => true,

        // En-tête
        'hero_logo' => null,
        'hero_background' => null,
        'hero_sign' => 'Serveur PvP Faction',
        'hero_sign_enabled' => true,
        'hero_subtitle' => 'Le serveur PvP Faction francophone. Forme ta faction, défends ta base, domine la map.',
        'hero_button' => 'Télécharger le launcher',
        'hero_show_players' => true,
        'hero_canopy' => true,

        // Launcher et liens
        'launcher_url' => null,
        'launcher_mac_url' => null,
        'launcher_linux_url' => null,
        'discord' => null,
        'shop_url' => null,

        // Section « Comment rejoindre »
        'join_enabled' => true,
        'about_title' => 'Rejoins la guerre sur *KoriaMC* !',
        'about_text' => 'Crée ou rejoins une faction, bâtis une base imprenable, pille tes ennemis et grimpe au classement. Sur KoriaMC, seule la faction la plus forte règne.',
        'join_card_title' => 'Comment rejoindre ?',
        'steps' => [
            ['title' => 'Télécharge le launcher', 'text' => 'Il installe tout ce qu\'il faut : version, mods et ressources.'],
            ['title' => 'Connecte-toi', 'text' => 'Ouvre le launcher et entre tes identifiants.'],
            ['title' => 'Clique sur Jouer', 'text' => 'Crée ta faction ou rejoins-en une, et pars au combat !'],
        ],
        'launcher_image' => null,
        'launcher_image_enabled' => true,
        'launcher_caption' => 'Le launcher KoriaMC',

        // Serveurs
        'servers_enabled' => true,
        'servers_title' => 'Nos *serveurs*',

        // Points forts
        'features_enabled' => true,
        'features_title' => 'Un champ de *bataille* vous attend !',
        'features' => [
            ['icon' => 'bi-shield-fill', 'title' => 'Factions', 'text' => 'Crée ta faction, recrute tes alliés et revendique ton territoire sur la map.'],
            ['icon' => 'bi-lightning-charge-fill', 'title' => 'Assauts', 'text' => 'Raid les bases ennemies, fais sauter leurs murs et repars avec leur butin.'],
            ['icon' => 'bi-trophy-fill', 'title' => 'Événements', 'text' => 'KOTH, totems et classements de factions avec des récompenses à la clé.'],
            ['icon' => 'bi-coin', 'title' => 'Économie', 'text' => 'Farm, vends à l\'hôtel des ventes et équipe ta faction pour la guerre.'],
        ],

        // Actualités
        'news_enabled' => true,
        'news_title' => 'Les dernières *actus* !',
        'news_count' => 3,
        'news_empty' => 'Aucune actualité pour le moment, revenez bientôt !',

        // Discord
        'discord_enabled' => true,
        'discord_title' => 'Rejoindre le *Discord*',
        'discord_text' => 'Trouve une faction, prépare tes assauts avec tes alliés et suis toutes les annonces du serveur.',
        'discord_button' => 'Rejoindre le Discord',

        // Appel final
        'cta_enabled' => true,
        'cta_title' => 'Prêt à rejoindre la *bataille* ?',
        'cta_text' => 'Monte ta faction, défends ton claim et inscris ton nom en haut du classement de KoriaMC !',
        'cta_button' => 'Télécharger le launcher',

        // Pied de page
        'footer_text' => 'Nous ne sommes pas affiliés à Mojang AB.',
        'footer_links_title' => 'Voir plus',
        'footer_social_title' => 'Suivez-nous !',
    ];

    /** Polices proposées et les graisses à charger pour chacune. */
    public const FONTS = [
        'Rubik' => '400;500;600;700;800;900',
        'Fredoka' => '400;500;600;700',
        'Baloo 2' => '400;500;600;700;800',
        'Nunito' => '400;500;600;700;800;900',
        'Lexend' => '400;500;600;700;800;900',
    ];

    private array $config;

    public function __construct()
    {
        $this->config = $this->merge(self::DEFAULTS, theme_config() ?? []);
    }

    /** Fusionne la config enregistrée sur les valeurs par défaut (les valeurs vides gardent le défaut). */
    private function merge(array $defaults, array $saved): array
    {
        foreach ($defaults as $key => $default) {
            if (! array_key_exists($key, $saved) || $saved[$key] === null || $saved[$key] === '') {
                continue;
            }

            $value = $saved[$key];

            if (is_bool($default)) {
                $defaults[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } elseif (is_int($default)) {
                $defaults[$key] = (int) $value;
            } elseif (is_array($default) && is_array($value)) {
                foreach ($default as $i => $item) {
                    $defaults[$key][$i] = is_array($item) ? $this->merge($item, (array) ($value[$i] ?? [])) : ($value[$i] ?? $item);
                }
            } else {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }

    public function get(string $key): mixed
    {
        return data_get($this->config, $key);
    }

    public function on(string $key): bool
    {
        return (bool) $this->get($key);
    }

    /** Valeur brute enregistrée (sans défaut), pour remplir les champs « optionnels ». */
    public function raw(string $key): mixed
    {
        return theme_config($key);
    }

    /** Titre avec les mots entre *astérisques* mis en couleur d'accent. */
    public function title(string $key): \Illuminate\Support\HtmlString
    {
        $text = e((string) $this->get($key));

        return new \Illuminate\Support\HtmlString(
            preg_replace('/\*(.+?)\*/u', '<span class="k-accent">$1</span>', $text)
        );
    }

    /** URL d'une image choisie dans la bibliothèque Azuriom, ou image du thème par défaut. */
    public function image(string $key, string $defaultAsset): string
    {
        $file = $this->get($key);

        return $file ? image_url($file) : theme_asset($defaultAsset);
    }

    public function color(string $key): string
    {
        $value = (string) $this->get($key);

        return preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? $value : self::DEFAULTS[$key];
    }

    public function font(): string
    {
        $font = (string) $this->get('font');

        return array_key_exists($font, self::FONTS) ? $font : 'Rubik';
    }

    public function fontUrl(): string
    {
        $font = $this->font();

        return 'https://fonts.googleapis.com/css2?family='.str_replace(' ', '+', $font).':wght@'.self::FONTS[$font].'&display=swap';
    }

    /** Variables CSS des couleurs choisies. */
    public function cssVars(): string
    {
        return sprintf(
            ':root{--k-leaf:%s;--k-wood:%s;--k-water:%s;--k-bg:%s;--k-font:\'%s\',system-ui,-apple-system,\'Segoe UI\',sans-serif}',
            $this->color('color_leaf'), $this->color('color_wood'), $this->color('color_water'), $this->color('color_bg'), $this->font()
        );
    }

    public function launcher(): ?string
    {
        return $this->get('launcher_url');
    }

    public function shop(): ?string
    {
        return $this->get('shop_url') ?: (\Illuminate\Support\Facades\Route::has('shop.home') ? route('shop.home') : null);
    }

    public function vote(): ?string
    {
        return \Illuminate\Support\Facades\Route::has('vote.home') ? route('vote.home') : null;
    }
};
