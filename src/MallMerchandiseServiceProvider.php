<?php

namespace WalkerChiu\MallMerchandise;

use Illuminate\Support\ServiceProvider;

class MallMerchandiseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
           __DIR__ .'/config/mall-merchandise.php' => config_path('wk-mall-merchandise.php'),
        ], 'config');

        // Publish migration files
        $from = __DIR__ .'/database/migrations/';
        $to   = database_path('migrations') .'/';
        $this->publishes([
            $from .'create_wk_mall_merchandise_table.php'
                => $to .date('Y_m_d_His', time()) .'_create_wk_mall_merchandise_table.php',
        ], 'migrations');

        $this->loadTranslationsFrom(__DIR__.'/translations', 'php-mall-merchandise');
        $this->publishes([
            __DIR__.'/translations' => resource_path('lang/vendor/php-mall-merchandise'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                config('wk-mall-merchandise.command.cleaner')
            ]);
        }

        config('wk-core.class.mall-merchandise.product')::observe(config('wk-core.class.mall-merchandise.productObserver'));
        config('wk-core.class.mall-merchandise.productLang')::observe(config('wk-core.class.mall-merchandise.productLangObserver'));
        config('wk-core.class.mall-merchandise.variant')::observe(config('wk-core.class.mall-merchandise.variantObserver'));
        config('wk-core.class.mall-merchandise.variantLang')::observe(config('wk-core.class.mall-merchandise.variantLangObserver'));
    }

    /**
     * Merges user's and package's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        if (!config()->has('wk-mall-merchandise')) {
            $this->mergeConfigFrom(
                __DIR__ .'/config/mall-merchandise.php', 'wk-mall-merchandise'
            );
        }

        $this->mergeConfigFrom(
            __DIR__ .'/config/mall-merchandise.php', 'mall-merchandise'
        );
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param String  $path
     * @param String  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        if (
            !(
                $this->app instanceof CachesConfiguration
                && $this->app->configurationIsCached()
            )
        ) {
            $config = $this->app->make('config');
            $content = $config->get($key, []);

            $config->set($key, array_merge(
                require $path, $content
            ));
        }
    }
}
