<?php

namespace SebastiaanLuca\Router;

use Illuminate\Contracts\Http\Kernel as AppKernel;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;
use SebastiaanLuca\Router\Routers\BootstrapRouter;

class RouterServiceProvider extends RouteServiceProvider
{
    
    /**
     * Map user-defined routers.
     */
    protected function registerUserRouters()
    {
        // We can safely assume the Kernel contract is the application's
        // HTTP kernel as it is bound in bootstrap/app.php to the actual
        // implementation.
        $routers = app(AppKernel::class)->getRouters();
        
        // Just instantiate each router, they handle the mapping itself
        foreach ($routers as $router) {
            app($router);
        }
    }
    
    
    
    /**
     * Register the service provider.
     */
    public function register()
    {
        // Define our router extension
        $this->app->singleton(ExtendedRouter::class, function ($app) {
            return new ExtendedRouter($app['events'], $app);
        });
        
        // Swap the default router with our extended router
        $this->app->alias(ExtendedRouter::class, 'router');
    }
    
    /**
     * Define your route model bindings, pattern filters, etc using the Bootstrap router.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {
        // Create a router that defines route patterns and whatnot
        $this->app->make(BootstrapRouter::class);
        
        // Map user-defined routers
        $this->registerUserRouters();
    }
    
}
