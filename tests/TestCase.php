<?php

namespace SebastiaanLuca\Router\Tests;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use ReflectionClass;
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
    protected function getPackageProviders($app)
    {
        return [RouterServiceProvider::class];
    }

    /**
     * Mock a class and optionally bind it in the IoC container.
     *
     * @param string $class
     * @param mixed $parameters
     *
     * @return \Mockery\MockInterface
     */
    protected function mock($class, $parameters = [])
    {
        $mock = Mockery::mock($class, $parameters);

        $this->app->instance($class, $mock);

        return $mock;
    }

    /**
     * Sets a private or protected class method to be publicly accessible.
     *
     * @param mixed $class
     * @param string $name
     *
     * @return \ReflectionMethod
     * @deprecated If you use this, your tests are probably bad.
     */
    protected function enablePublicAccessOfMethod($class, $name)
    {
        $reflection = new ReflectionClass($class);
        $method = $reflection->getMethod($name);

        $method->setAccessible(true);

        return $method;
    }

    /**
     * Sets a private or protected class property to be publicly accessible.
     *
     * @param $class
     * @param $property
     *
     * @return \ReflectionProperty
     * @deprecated If you use this, your tests are probably bad.
     */
    protected function enablePublicAccessOfProperty($class, $property)
    {
        $reflection = new ReflectionClass($class);
        $property = $reflection->getProperty($property);

        $property->setAccessible(true);

        return $property;
    }

    /**
     * Set the value of a private or protected class property.
     *
     * @param object $instance
     * @param string $property
     * @param mixed $value
     *
     * @return \ReflectionProperty
     * @deprecated If you use this, your tests are probably bad.
     */
    protected function setValueOfInternalProperty($instance, $property, $value)
    {
        $property = $this->enablePublicAccessOfProperty($instance, $property);

        $property->setValue($instance, $value);

        return $property;
    }
}
