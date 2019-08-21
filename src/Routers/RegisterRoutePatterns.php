<?php

declare(strict_types=1);

namespace SebastiaanLuca\Router\Routers;

/**
 * Bootstrap commonly used route parameter patterns.
 *
 * @package SebastiaanLuca\Router\Routers
 */
class RegisterRoutePatterns extends Router
{
    /**
     * Register the routes.
     *
     * @return void
     */
    public function map() : void
    {
        $this->router->pattern('id', '\d+');
        $this->router->pattern('hash', '[a-z0-9]+');
        $this->router->pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
        $this->router->pattern('slug', '[a-z0-9-]+');
        $this->router->pattern('token', '[a-zA-Z0-9]{64}');

        // Allow full domain routing by including dots in the domain regex pattern. Same
        // use-case as subdomains, only this enables you to handle i.e. www.site1.com
        // and www.site2.com within the same Laravel application with a single route or
        // route group.
        $this->router->pattern('domain', '[a-z0-9.]+');
    }
}
