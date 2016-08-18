<?php

namespace SebastiaanLuca\Router\Tests;

use SebastiaanLuca\Router\Kernel;
use SebastiaanLuca\Router\RouterServiceProvider;

class ServiceProviderTest extends TestCase
{
    public function testItInstantiatesAllUserKernelRouters()
    {
        // The kernel routers
        $routers = [
            'router1',
            'router2',
            'router3',
        ];
        
        //
        $kernel = $this->mock(Kernel::class, [null => null]);
        $kernel->shouldReceive('getRouters')->andReturn($routers);
        
        // 3 routers should each be instantiated once
        $application = $this->mock('application');
        
        $application->shouldReceive('make')->once()->with($routers[0]);
        $application->shouldReceive('make')->once()->with($routers[1]);
        $application->shouldReceive('make')->once()->with($routers[2]);
        
        //
        $serviceProvider = $this->mock(RouterServiceProvider::class, [null => null])->shouldAllowMockingProtectedMethods();
        
        // Allow to call the real method implementation
        $serviceProvider->shouldReceive('getApplicationKernel')->andReturn($kernel);
        $serviceProvider->shouldReceive('registerUserRouters')->passthru();
        
        $this->setValueOfInternalProperty($serviceProvider, 'app', $application);
        
        $serviceProvider->registerUserRouters();
    }
}