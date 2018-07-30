<?php

namespace SebastiaanLuca\Router;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use SebastiaanLuca\Router\Kernel\RegistersRouters;

class RouterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() : void
    {
        $kernel = app(Kernel::class);

        if (class_uses_trait($kernel, RegistersRouters::class)) {
            $kernel->bootRouters();
        }
    }
}
