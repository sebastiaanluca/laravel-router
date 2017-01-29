<?php

namespace SebastiaanLuca\Router\Tests\Unit;

use Illuminate\Contracts\Http\Kernel as AppKernel;
use SebastiaanLuca\Router\Kernel as PackageKernel;
use SebastiaanLuca\Router\RouterServiceProvider;
use SebastiaanLuca\Router\Tests\TestCase;

class KernelTest extends TestCase
{
    public function testItCreatesRouters()
    {
        // Verify that the IoC container successfully created
        // the router that was registered (as a string) in the
        // HTTP kernel.
        app()->bind('MyTestRouter', function () {
            // Assert that the router has been created (i.e.
            // the closure was resolved by the application)
            $this->assertTrue(true);
        });

        // Bind our test kernel as the application's active HTTP kernel
        app()->singleton(AppKernel::class, get_class($this->createKernel()));

        // Go through the flow of getting the registered routers
        // from the kernel and instantiating them.
        app()->register(RouterServiceProvider::class);
    }

    /**
     * Get package providers.
     *
     * Override to disable auto-loading the package's service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app) : array
    {
        return [];
    }

    /**
     * @return \SebastiaanLuca\Router\Kernel
     */
    protected function createKernel() : PackageKernel
    {
        return new class(app(), app('router')) extends PackageKernel
        {
            /**
             * The routers to automatically map.
             *
             * @var array
             */
            protected $routers = [
                'MyTestRouter',
            ];
        };
    }
}
