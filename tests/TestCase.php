<?php

declare(strict_types=1);

namespace SebastiaanLuca\Router\Tests;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use SebastiaanLuca\Router\RouterServiceProvider;

class TestCase extends OrchestraTestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app) : array
    {
        return [RouterServiceProvider::class];
    }

    /**
     * Mock a class and bind it in the IoC container.
     *
     * @param string $class
     * @param mixed $parameters
     *
     * @return \Mockery\MockInterface|$class
     */
    protected function mock($class, $parameters = []) : MockInterface
    {
        $mock = Mockery::mock($class, $parameters);

        $this->app->instance($class, $mock);

        return $mock;
    }
}
