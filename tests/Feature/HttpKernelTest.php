<?php

declare(strict_types=1);

namespace SebastiaanLuca\Router\Tests\Feature;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use SebastiaanLuca\Router\Kernel\RegistersRouters;
use SebastiaanLuca\Router\RouterServiceProvider;
use SebastiaanLuca\Router\Tests\TestCase;

class HttpKernelTest extends TestCase
{
    /**
     * @test
     */
    public function it boots routers() : void
    {
        // Verify that the IoC container successfully created the router that
        // was registered (as a string) in the HTTP kernel. The actual router
        // test itself is done in another test.
        $this->app->bind('MyTestRouter', function () {
            $this->assertTrue(true);
        });

        // Bind our test kernel as the application's active HTTP kernel
        $this->app->singleton(Kernel::class, get_class($this->createKernel()));

        // Go through the flow of getting the registered routers from
        // the kernel and instantiating them.
        (new RouterServiceProvider($this->app))->boot();
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
     * @return \Illuminate\Foundation\Http\Kernel
     */
    protected function createKernel() : HttpKernel
    {
        return new class(app(), app('router')) extends HttpKernel
        {
            use RegistersRouters;

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
