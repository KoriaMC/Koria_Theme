<?php

$text = fn (int $max) => ['nullable', 'string', 'max:'.$max];
$bool = ['nullable', 'boolean'];
$color = ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'];

return [
    // Général
    'color_leaf' => $color,
    'color_wood' => $color,
    'color_water' => $color,
    'color_bg' => $color,
    'font' => ['nullable', 'string', 'in:Rubik,Fredoka,Baloo 2,Nunito,Lexend'],
    'animations' => $bool,
    'admin_skin' => $bool,

    // Navigation
    'nav_shop_button' => $bool,
    'nav_shop_label' => $text(40),
    'nav_show_discord' => $bool,

    // En-tête
    'hero_logo' => $text(255),
    'hero_background' => $text(255),
    'hero_sign' => $text(60),
    'hero_sign_enabled' => $bool,
    'hero_subtitle' => $text(250),
    'hero_button' => $text(60),
    'hero_show_players' => $bool,
    'hero_canopy' => $bool,

    // Launcher et liens
    'launcher_url' => $text(255),
    'launcher_mac_url' => $text(255),
    'launcher_linux_url' => $text(255),
    'discord' => ['nullable', 'url', 'max:255'],
    'shop_url' => $text(255),

    // Comment rejoindre
    'join_enabled' => $bool,
    'about_title' => $text(120),
    'about_text' => $text(1000),
    'join_card_title' => $text(60),
    'steps' => ['nullable', 'array', 'size:3'],
    'steps.*.title' => $text(60),
    'steps.*.text' => $text(200),
    'launcher_image' => $text(255),
    'launcher_image_enabled' => $bool,
    'launcher_caption' => $text(60),

    // Serveurs
    'servers_enabled' => $bool,
    'servers_title' => $text(120),

    // Points forts
    'features_enabled' => $bool,
    'features_title' => $text(120),
    'features' => ['nullable', 'array', 'size:4'],
    'features.*.icon' => ['nullable', 'string', 'max:50', 'regex:/^bi-[a-z0-9-]+$/'],
    'features.*.title' => $text(40),
    'features.*.text' => $text(200),

    // Actualités
    'news_enabled' => $bool,
    'news_title' => $text(120),
    'news_count' => ['nullable', 'integer', 'min:1', 'max:6'],
    'news_empty' => $text(200),

    // Discord
    'discord_enabled' => $bool,
    'discord_title' => $text(120),
    'discord_text' => $text(400),
    'discord_button' => $text(60),

    // Appel final
    'cta_enabled' => $bool,
    'cta_title' => $text(120),
    'cta_text' => $text(400),
    'cta_button' => $text(60),

    // Pied de page
    'footer_text' => $text(300),
    'footer_links_title' => $text(40),
    'footer_social_title' => $text(40),
];
