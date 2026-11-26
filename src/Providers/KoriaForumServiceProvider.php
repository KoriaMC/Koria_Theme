<?php

namespace Azuriom\Plugin\KoriaForum\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\Permission;

class KoriaForumServiceProvider extends BasePluginServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadMigrations();
        $this->registerRouteDescriptions();

        Permission::registerPermissions([
            'koriaforum.moderate' => 'koriaforum::messages.permission',
        ]);
    }

    /**
     * Routes proposées dans Admin > Navbar (type « Plugin »).
     */
    protected function routeDescriptions(): array
    {
        return [
            'koriaforum.index' => trans('koriaforum::messages.title'),
        ];
    }
}
