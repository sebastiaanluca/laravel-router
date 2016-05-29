<?php

namespace SebastiaanLuca\Router\Tests\Routers;

use SebastiaanLuca\Router\Routers\Router;
use SebastiaanLuca\Router\Tests\TestCase;

class BaseRouterTest extends TestCase
{
    
    /**
     * Create a new router.
     *
     * @return \Mockery\MockInterface|\SebastiaanLuca\Router\Routers\Router
     */
    protected function createRouter()
    {
        // Create a basic router and implement the map method.
        // makePartial() prevents the constructor from being called.
        return $this->mock(Router::class, [
            null => null,
        ])->makePartial();
    }
    
    
    
    public function testItCallsMapOnInitialization()
    {
        $router = $this->createRouter();
        
        $router->shouldReceive('map')->once();
        
        // Manually initialize the class
        $router->__construct(app('router'));
    }
    
    public function testItSetsNamespace()
    {
        $router = $this->createRouter();
        
        $this->setValueOfInternalProperty($router, 'namespace', 'App\\Http\\Routers');
        
        $namespace = $router->getNamespace();
        
        $this->assertEquals('App\\Http\\Routers', $namespace);
    }
    
    public function testItCanAddASuffixToANamespace()
    {
        $router = $this->createRouter();
        
        $this->setValueOfInternalProperty($router, 'namespace', 'App\\Http\\Routers');
        
        $namespace = $router->getNamespace('Sub');
        
        $this->assertEquals('App\\Http\\Routers\\Sub', $namespace);
    }
    
}