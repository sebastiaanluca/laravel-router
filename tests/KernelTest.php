<?php

namespace SebastiaanLuca\Router\Tests;

use SebastiaanLuca\Router\Kernel;

class KernelTest extends TestCase
{
    
    /**
     * @return \Mockery\MockInterface|\SebastiaanLuca\Router\Kernel
     */
    protected function createKernel()
    {
        return $this->mock(Kernel::class, [
            $this->app,
            $this->app['router'],
        ])->makePartial()->shouldAllowMockingProtectedMethods();
    }
    
    
    
    public function testItResetsTheLocalRouter()
    {
        $kernel = $this->createKernel();
        $property = $this->enablePublicAccessOfProperty($kernel, 'router');
        
        // The router we're using as replacement
        $router = $this->mock('mockedRouter');
        
        // Bind our mocked router into the IoC
        $this->app->instance('router', $router);
        
        // Kick-off
        $kernel->dispatchToRouter();
        
        // Local router should now equal our mocked router instead
        // of the one we used when instantiating the class
        $this->assertEquals($router, $property->getValue($kernel));
    }
    
    public function testItReturnsAllRegisteredRouters()
    {
        $kernel = $this->createKernel();
        
        $routers = [
            'route1',
            'route2',
            'route3',
        ];
        
        $this->setValueOfInternalProperty($kernel, 'routers', $routers);
        
        $this->assertEquals($routers, $kernel->getRouters());
    }
    
}