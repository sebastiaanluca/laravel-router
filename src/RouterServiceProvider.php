<?php

namespace SebastiaanLuca\Router;

use Illuminate\Contracts\Http\Kernel as AppKernel;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;
use SebastiaanLuca\Router\Routers\BootstrapRouter;

class RouterServiceProvider extends RouteServiceProvider
{
    
    /**
     * Get the application's kernel implementation.
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    protected function getApplicationKernel()
    {
        return app(AppKernel::class);
    }
    
    /**
     * Map user-defined routers.
     */
    protected function registerUserRouters()
    {
        // We can safely assume the Kernel contract is the application's
        // HTTP kernel as it is bound in bootstrap/app.php to the actual
        // implementation. Still need to check if it's the package's
        // custom kernel though as that is an optional setup choice.
        
        $kernel = $this->getApplicationKernel();
        
        if (! $kernel instanceof Kernel) {
            return;
        }
        
        $routers = $kernel->getRouters();
        
        // Just instantiate each router as they handle the mapping itself
        foreach ($routers as $router) {
            $this->app->make($router);
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
