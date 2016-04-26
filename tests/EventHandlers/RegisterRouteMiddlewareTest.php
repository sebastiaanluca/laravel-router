<?php

namespace SebastiaanLuca\Router\Tests\EventHandlers;

use Illuminate\Foundation\Bootstrap\BootProviders;
use SebastiaanLuca\Router\EventHandlers\RegisterRouteMiddleware;
use SebastiaanLuca\Router\Tests\TestCase;

class RegisterRouteMiddlewareTest extends TestCase
{
    
    public function testRegisterRouteMiddlewareEventHandlerGetsCalled()
    {
        $handler = $this->mock(RegisterRouteMiddleware::class);
        
        $handler->shouldReceive('handle')->once();
        
        event('bootstrapped: ' . BootProviders::class);
    }
    
    public function testRegisterRouteMiddlewareEventHandlerInitiatesLinkingMiddleware()
    {
        $router = $this->mock('router');
        
        $router->shouldReceive('linkNamedRouteMiddleware')->once();
        
        $handler = app(RegisterRouteMiddleware::class);
        
        $handler->handle(null);
    }
    
}