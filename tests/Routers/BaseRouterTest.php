<?php

namespace SebastiaanLuca\Router\Tests\Routers;

use SebastiaanLuca\Router\Routers\BaseRouter;
use SebastiaanLuca\Router\Tests\TestCase;

class BaseRouterTest extends TestCase
{
    
    /**
     * Create a new router.
     *
     * @return \Mockery\MockInterface
     */
    protected function createRouter()
    {
        // Create a basic router and implement the map method.
        // makePartial() prevents the constructor from being called.
        return $this->mock(BaseRouter::class, [
            'map' => null,
        ])->makePartial();
    }
    
    
    
    public function testItCallsMapOnInitialization()
    {
        $router = $this->createRouter();
        
        $router->shouldReceive('map')->once();
        
        // Manually initialize the class
        $router->__construct(app('router'));
    }
    
    //    public function testItSetsNamespace()
    //    {
    //        // TODO
    //    }
    //    
    //    public function testItCanAddASuffixToANamespace()
    //    {
    //        // TODO
    //    }
    
}