<?php

namespace SebastiaanLuca\Router\Tests\Unit;

use Illuminate\Contracts\Routing\Registrar as Router;
use SebastiaanLuca\Router\Routers\RegisterRoutePatterns;
use SebastiaanLuca\Router\Tests\TestCase;

class RoutePatternTest extends TestCase
{
    public function test it maps the id pattern()
    {
        $this->assertRoutePattern(function (Router $registrar) {
            $registrar->shouldReceive('pattern')->once()->with('id', '\d+');
        });
    }

    public function test it maps the hash pattern()
    {
        $this->assertRoutePattern(function (Router $registrar) {
            $registrar->shouldReceive('pattern')->once()->with('hash', '[a-z0-9]+');
        });
    }

    public function test it maps the uuid pattern()
    {
        $this->assertRoutePattern(function (Router $registrar) {
            $registrar->shouldReceive('pattern')->once()->with('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
        });
    }

    public function test it maps the slug pattern()
    {
        $this->assertRoutePattern(function (Router $registrar) {
            $registrar->shouldReceive('pattern')->once()->with('slug', '[a-z0-9-]+');
        });
    }

    public function test it maps the token pattern()
    {
        $this->assertRoutePattern(function (Router $registrar) {
            $registrar->shouldReceive('pattern')->once()->with('token', '[a-zA-Z0-9]{100}');
        });
    }

    public function test it maps the domain pattern()
    {
        $this->assertRoutePattern(function (Router $registrar) {
            $registrar->shouldReceive('pattern')->once()->with('domain', '[a-z0-9.]+');
        });
    }

    /**
     * @param callable $assertion
     */
    protected function assertRoutePattern(callable $assertion)
    {
        $registrar = $this->mock(Router::class)->shouldIgnoreMissing();

        $assertion($registrar);

        $this->createBootstrapRouter($registrar);
    }

    /**
     * @param \Mockery\MockInterface|\Illuminate\Contracts\Routing\Registrar $registrar
     *
     * @return \SebastiaanLuca\Router\Routers\RegisterRoutePatterns
     */
    protected function createBootstrapRouter(Router $registrar) : RegisterRoutePatterns
    {
        return new RegisterRoutePatterns($registrar);
    }
}
