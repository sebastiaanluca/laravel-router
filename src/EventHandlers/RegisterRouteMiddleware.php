<?php

namespace SebastiaanLuca\Router\EventHandlers;

/**
 * Class RegisterRouteMiddleware
 *
 * Registers all named route middleware when all service providers have been registered and booted.
 *
 * @package SebastiaanLuca\Router\EventHandlers
 */
class RegisterRouteMiddleware
{
    
    /**
     * Handle the event.
     */
    public function handle()
    {
        app('router')->linkNamedRouteMiddleware();
    }
    
}
