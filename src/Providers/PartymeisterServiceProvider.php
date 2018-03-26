<?php

namespace Partymeister\Slides\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Partymeister\Slides\Console\Commands\PartymeisterSlidesGenerateCompetitionCommand;
use Partymeister\Slides\Console\Commands\PartymeisterSlidesGenerateEntryCommand;

class PartymeisterServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->config();
        $this->routes();
        $this->routeModelBindings();
        $this->translations();
        $this->views();
        $this->navigationItems();
        $this->permissions();
        $this->registerCommands();
        $this->migrations();
        $this->publishResourceAssets();
    }


    public function config()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/partymeister-slides-fonts.php', 'partymeister-slides-fonts');
    }


    public function publishResourceAssets()
    {
        $assets = [
            __DIR__ . '/../../resources/assets/images' => public_path('images'),
            __DIR__ . '/../../resources/assets/css' => resource_path('assets/css'),
            __DIR__ . '/../../resources/assets/js'  => resource_path('assets/js'),
        ];

        $this->publishes($assets, 'partymeister-slides-install');
    }


    public function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PartymeisterSlidesGenerateCompetitionCommand::class,
                PartymeisterSlidesGenerateEntryCommand::class,
            ]);
        }
    }


    public function migrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }


    public function permissions()
    {
        $config = $this->app['config']->get('motor-backend-permissions', []);
        $this->app['config']->set('motor-backend-permissions',
            array_replace_recursive(require __DIR__ . '/../../config/motor-backend-permissions.php', $config));
    }


    public function routes()
    {
        if ( ! $this->app->routesAreCached()) {
            require __DIR__ . '/../../routes/web.php';
            require __DIR__ . '/../../routes/api.php';
        }
    }


    public function translations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'partymeister-slides');

        $this->publishes([
            __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/partymeister-slides'),
        ], 'motor-backend-translations');
    }


    public function views()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'partymeister-slides');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/partymeister-slides'),
        ], 'motor-backend-views');
    }


    public function routeModelBindings()
    {
        Route::bind('slide', function ($id) {
            return \Partymeister\Slides\Models\Slide::findOrFail($id);
        });
        Route::bind('slide_template', function ($id) {
            return \Partymeister\Slides\Models\SlideTemplate::findOrFail($id);
        });
        Route::bind('playlist', function ($id) {
            return \Partymeister\Slides\Models\Playlist::findOrFail($id);
        });
        Route::bind('playlist_item', function ($id) {
            return \Partymeister\Slides\Models\PlaylistItem::findOrFail($id);
        });
        Route::bind('transition', function ($id) {
            return \Partymeister\Slides\Models\Transition::findOrFail($id);
        });
        Route::bind('slide_client', function ($id) {
            return \Partymeister\Slides\Models\SlideClient::findOrFail($id);
        });
    }


    public function navigationItems()
    {
        $config = $this->app['config']->get('motor-backend-navigation', []);
        $this->app['config']->set('motor-backend-navigation',
            array_replace_recursive(require __DIR__ . '/../../config/motor-backend-navigation.php', $config));
    }
}
