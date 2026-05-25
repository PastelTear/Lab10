<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole() && ! $this->app->runningUnitTests()) {
            return;
        }

        $this->app->booted(function (): void {
            $request = $this->app->make('request');

            if (! $request->hasHeader('Host')) {
                return;
            }

            $basePath = $request->getBasePath();

            if ($basePath !== '') {
                config(['session.path' => $basePath]);
                URL::forceRootUrl($request->getSchemeAndHttpHost().$basePath);

                return;
            }

            $appUrl = config('app.url');

            if (is_string($appUrl) && $appUrl !== '') {
                URL::forceRootUrl($appUrl);
            }
        });
    }
}
