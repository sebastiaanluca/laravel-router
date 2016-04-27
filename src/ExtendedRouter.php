<?php

namespace SebastiaanLuca\Router;

use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Bootstrap\BootProviders;
use Illuminate\Routing\Router;
use SebastiaanLuca\Router\EventHandlers\RegisterRouteMiddleware;

/**
 * Class ExtendedRouter
 *
 * An extension to the application's router to provide extra functionality.
 *
 * @package SebastiaanLuca\Routing
 */
class ExtendedRouter extends Router
{
    
    /**
     * The registered middleware for named routes.
     *
     * @var array
     */
    protected $routeMiddleware = [];
    
    /**
     * Indicates if all the service providers have been loaded.
     *
     * @var bool
     */
    protected $isBootstrapped = false;
    
    /**
     * Create a new Router instance.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @param \Illuminate\Container\Container $container
     */
    public function __construct(Dispatcher $events, Container $container = null)
    {
        parent::__construct($events, $container);
        
        // Add all registered named route middlewares after all service providers have been loaded
        app('events')->listen('bootstrapped: ' . BootProviders::class, RegisterRouteMiddleware::class);
    }
    
    
    
    /**
     * Add middleware to a named route.
     *
     * Optionally handles wildcard named routes.
     *
     * @param string $name
     * @param array $middleware
     */
    protected function addMiddlewareToRoute($name, array $middleware)
    {
        // Get all registered app routes
        $routes = $this->getRoutes()->getIterator();
        
        // Check if the named route is a wildcard route
        $wildcard = false;
        
        if (ends_with($name, '*')) {
            $wildcard = true;
            $name = substr($name, 0, -1);
        }
        
        /** @var \Illuminate\Routing\Route $route */
        foreach ($routes as $route) {
            // Only apply middleware if route name matches 1-to-1 and it's not a wildcard
            // or if it is a wildcard and the first part before the asterisk is the beginning
            // of the route's name.
            if ((! $wildcard && $route->getName() !== $name) || ($wildcard && ! starts_with($route->getName(), $name))) {
                continue;
            }
            
            // If we have any middleware registered to the route, apply it
            $route->middleware($middleware);
        }
    }
    
    
    
    /**
     * Register middleware to add to a named route.
     *
     * AKA Route::when() but for middleware. Use an asterisk (*) for wildcard entries. E.g.
     * `users.*` filters all user routes.
     *
     * @param string|array $routes
     * @param string|array $middleware
     */
    public function registerNamedRouteMiddleware($routes, $middleware)
    {
        if (is_string($routes)) {
            $routes = [$routes];
        }
        
        if (is_string($middleware)) {
            $middleware = [$middleware];
        }
        
        foreach ($routes as $route) {
            // Register the middleware and link it to the given route.
            // Also make sure the middleware to be applied is unique
            // (i.e. only add a certain middleware once)
            $this->routeMiddleware[$route] = array_values(array_unique(array_merge(array_get($this->routeMiddleware, $route, []), $middleware)));
            
            // App is ready to go and all routes have been registered,
            // so we can immediately link the middleware to the route
            if ($this->isBootstrapped) {
                $this->addMiddlewareToRoute($route, $this->routeMiddleware[$route]);
            }
        }
    }
    
    /**
     * Link all registered middleware to the corresponding routes.
     *
     * The call to this method is delayed until all service providers have been registered and
     * booted. This way all routes are defined before we try to apply any middleware.
     */
    public function linkNamedRouteMiddleware()
    {
        $this->isBootstrapped = true;
        
        foreach ($this->routeMiddleware as $route => $middleware) {
            $this->addMiddlewareToRoute($route, $middleware);
        }
    }
    
}
