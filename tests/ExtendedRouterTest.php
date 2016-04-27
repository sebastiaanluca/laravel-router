<?php

namespace SebastiaanLuca\Router\Tests;

use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Bootstrap\BootProviders;
use SebastiaanLuca\Router\ExtendedRouter;

class ExtendedRouterTest extends TestCase
{
    
    /**
     * Create a new router.
     *
     * @param bool $partial
     *
     * @return \Mockery\MockInterface
     */
    protected function createExtendedRouter($partial = true)
    {
        $router = $this->mock(ExtendedRouter::class, [null => null]);
        
        if ($partial) {
            $router = $router->makePartial();
        }
        
        return $router->shouldAllowMockingProtectedMethods();
    }
    
    
    
    public function testDefaultRouterGotSwappedWithExtendedRouter()
    {
        $this->assertInstanceOf(ExtendedRouter::class, app('router'));
    }
    
    public function testRouteFacadeGotSwappedWithExtendedRouter()
    {
        $this->assertInstanceOf(ExtendedRouter::class, \Route::getFacadeRoot());
    }
    
    
    
    public function testItAddsMiddlewareToANamedRoute()
    {
        // Reverse instantation
        
        // 'mockedRoute' instead of just 'route' to prevent double-declaration of Route facade.
        // Somehow it keeps nagging about that.
        $route1 = $this->mock('mockedRoute');
        $route1->shouldReceive('getName')->andReturn('route1');
        $route1->shouldReceive('middleware')->with(['middlewareA', 'middlewareB'])->once();
        
        $route2 = $this->mock('mockedRoute');
        $route2->shouldReceive('getName')->andReturn('route2');
        $route2->shouldReceive('middleware')->never();
        
        // Route setup
        $routes = [
            $route1,
            $route2,
        ];
        
        // Intermediate route collection setup
        $collection = $this->mock('collection');
        $collection->shouldReceive('getIterator')->andReturn($routes);
        
        $router = $this->createExtendedRouter();
        $router->shouldReceive('getRoutes')->andReturn($collection);
        
        // Use the real method instead of mocking it
        $router->shouldReceive('addMiddlewareToRoute')->passthru();
        
        $router->addMiddlewareToRoute('route1', ['middlewareA', 'middlewareB']);
    }
    
    public function testItAddsMiddlewareToANamedRouteUsingAWildcard()
    {
        // Reverse instantation
        
        $route1 = $this->mock('mockedRoute');
        $route1->shouldReceive('getName')->andReturn('route.group');
        $route1->shouldReceive('middleware')->with(['middlewareA', 'middlewareB'])->once();
        
        $route2 = $this->mock('mockedRoute');
        $route2->shouldReceive('getName')->andReturn('route.group');
        $route2->shouldReceive('middleware')->with(['middlewareA', 'middlewareB'])->once();
        
        $route3 = $this->mock('mockedRoute');
        $route3->shouldReceive('getName')->andReturn('route3');
        $route3->shouldReceive('middleware')->never();
        
        // Route setup
        $routes = [
            $route1,
            $route2,
            $route3,
        ];
        
        // Intermediate route collection setup
        $collection = $this->mock('collection');
        $collection->shouldReceive('getIterator')->andReturn($routes);
        
        $router = $this->createExtendedRouter();
        $router->shouldReceive('getRoutes')->andReturn($collection);
        
        // Use the real method instead of mocking it
        $router->shouldReceive('addMiddlewareToRoute')->passthru();
        
        $router->addMiddlewareToRoute('route.*', ['middlewareA', 'middlewareB']);
    }
    
    
    
    public function testItRegistersMiddleware()
    {
        $router = $this->createExtendedRouter(false);
        
        // Pretend the application has fully booted and
        // allow actual registration of middleware
        $this->setValueOfInternalProperty($router, 'isBootstrapped', true);
        
        // Use the real method instead of mocking it
        $router->shouldReceive('registerNamedRouteMiddleware')->passthru();
        
        // Auto-mocked method which we're expecting to get called
        $router->shouldReceive('addMiddlewareToRoute')->with('mockedRoute', ['middleware'])->once();
        
        // Kick-off
        $router->registerNamedRouteMiddleware('mockedRoute', 'middleware');
    }
    
    public function testItRegistersMiddlewareForMultipleRoutes()
    {
        $router = $this->createExtendedRouter(false);
        
        // Pretend the application has fully booted and
        // allow actual registration of middleware
        $this->setValueOfInternalProperty($router, 'isBootstrapped', true);
        
        // Use the real method instead of mocking it
        $router->shouldReceive('registerNamedRouteMiddleware')->passthru();
        
        // Auto-mocked method which we're expecting to get called
        $router->shouldReceive('addMiddlewareToRoute')->with('route1', ['middleware'])->once();
        $router->shouldReceive('addMiddlewareToRoute')->with('route2', ['middleware'])->once();
        $router->shouldReceive('addMiddlewareToRoute')->with('route3', ['middleware'])->once();
        
        // Kick-off
        $router->registerNamedRouteMiddleware(['route1', 'route2', 'route3'], 'middleware');
    }
    
    public function testItRegistersMultipleMiddleware()
    {
        $router = $this->createExtendedRouter(false);
        
        // Pretend the application has fully booted and
        // allow actual registration of middleware
        $this->setValueOfInternalProperty($router, 'isBootstrapped', true);
        
        // Use the real method instead of mocking it
        $router->shouldReceive('registerNamedRouteMiddleware')->passthru();
        
        // Auto-mocked method which we're expecting to get called
        $router->shouldReceive('addMiddlewareToRoute')->with('mockedRoute', ['middlewareA', 'middlewareB', 'middlewareC'])->once();
        
        // Kick-off
        $router->registerNamedRouteMiddleware('mockedRoute', ['middlewareA', 'middlewareB', 'middlewareC']);
    }
    
    public function testItFiltersOutDuplicateMiddlewareWhenRegistering()
    {
        $router = $this->createExtendedRouter(false);
        
        // Pretend the application has fully booted and
        // allow actual registration of middleware
        $this->setValueOfInternalProperty($router, 'isBootstrapped', true);
        
        // Use the real method instead of mocking it
        $router->shouldReceive('registerNamedRouteMiddleware')->passthru();
        
        // Auto-mocked method which we're expecting to get called
        $router->shouldReceive('addMiddlewareToRoute')->with('mockedRoute', ['middlewareA', 'middlewareB', 'middlewareC'])->once();
        
        // Kick-off
        $router->registerNamedRouteMiddleware('mockedRoute', ['middlewareA', 'middlewareA', 'middlewareB', 'middlewareB', 'middlewareB', 'middlewareB', 'middlewareC']);
    }
    
    
    
    /**
     * After bootstrapping the service providers, the registered (but not yet applied) middleware
     * should be added to the corresponding routes.
     */
    public function testItAddsPreviouslyRegisteredMiddlewareToRoutes()
    {
        $router = $this->createExtendedRouter();
        
        $this->setValueOfInternalProperty($router, 'routeMiddleware', [
            'route1' => ['middleware'],
            'route2' => ['middleware'],
            'route3' => ['middleware'],
        ]);
        
        $router->shouldReceive('addMiddlewareToRoute')->times(3);
        
        $router->linkNamedRouteMiddleware();
    }
    
}