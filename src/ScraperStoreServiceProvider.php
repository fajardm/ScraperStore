<?php

namespace fajardm\ScraperStore;

use Illuminate\Support\ServiceProvider;

class ScraperStoreServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSkeleton();
    }

    private function registerSkeleton()
    {
        $this->app->bind('scraper', function ($app) {
            return new Scraper($app);
        });
    }
}