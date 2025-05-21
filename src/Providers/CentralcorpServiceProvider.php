<?php

namespace Azuriom\Plugin\Centralcorp\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Illuminate\Routing\Router;

class CentralcorpServiceProvider extends BasePluginServiceProvider
{
    /**
     * The plugin's global HTTP middleware stack.
     */
    protected array $middleware = [
        // \Azuriom\Plugin\Centralcorp\Middleware\ExampleMiddleware::class,
    ];

    /**
     * The plugin's route middleware groups.
     */
    protected array $middlewareGroups = [];

    /**
     * The plugin's route middleware.
     */
    protected array $routeMiddleware = [
        // 'example' => \Azuriom\Plugin\Centralcorp\Middleware\ExampleRouteMiddleware::class,
    ];

    /**
     * The policy mappings for this plugin.
     *
     * @var array<string, string>
     */
    protected array $policies = [
        // User::class => UserPolicy::class,
    ];

    /**
     * Register any plugin services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any plugin services.
     */
    public function boot(): void
    {
        // $this->registerPolicies();

        $this->loadViews();

        $this->loadTranslations();

        $this->loadMigrations();

        $this->registerRouteDescriptions();

        $this->registerAdminNavigation();

        $this->registerUserNavigation();

    }

    /**
     * Returns the routes that should be able to be added to the navbar.
     *
     * @return array<string, string>
     */
    protected function routeDescriptions(): array
    {
        return [
            //
        ];
    }

    /**
     * Return the admin navigations routes to register in the dashboard.
     *
     * @return array<string, array<string, string>>
     */
    protected function adminNavigation(): array
    {
        return [
            'centralcorp' => [
                'type' => 'dropdown',
                'name' => 'CentralCorp',
                'icon' => 'bi bi-hdd-network',
                'route' => 'centralcorp.admin.*',
                'items' => [
                    'centralcorp.admin.general' => trans('centralcorp::admin.general'),
                    'centralcorp.admin.rpc' => trans('centralcorp::admin.rpc'),
                    'centralcorp.admin.server' => trans('centralcorp::admin.server'),
                    'centralcorp.admin.loader' => trans('centralcorp::admin.loader'),
                    'centralcorp.admin.mods' => trans('centralcorp::admin.mods'),
                    'centralcorp.admin.ui' => trans('centralcorp::admin.ui'),
                    'centralcorp.admin.whitelist' => trans('centralcorp::admin.whitelist'),
                    'centralcorp.admin.ignore' => trans('centralcorp::admin.ignore'),
                    'centralcorp.admin.roles.index' => trans('centralcorp::admin.roles-bg'),
                ],
            ],
        ];
    }

    /**
     * Return the user navigations routes to register in the user menu.
     *
     * @return array<string, array<string, string>>
     */
    protected function userNavigation(): array
    {
        return [
            //
        ];
    }
}
