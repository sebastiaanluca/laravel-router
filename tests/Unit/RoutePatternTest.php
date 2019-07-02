<?php

namespace SebastiaanLuca\Router\Tests\Unit;

use Illuminate\Contracts\Routing\Registrar as Router;
use SebastiaanLuca\Router\Routers\RegisterRoutePatterns;
use SebastiaanLuca\Router\Tests\TestCase;

class RoutePatternTest extends TestCase
{
    /**
     * @test
     */
    public function it maps the id pattern() : void
    {
        $this->assertRoutePattern(function (Router $registrar) {
            $registrar->shouldReceive('pattern')->once()->with('id', '\d+');
        });
    }

    /**
     * @test
     */
    public function it maps the hash pattern() : void
    {
        $this->assertRoutePattern(function (Router $registrar) {
            $registrar->shouldReceive('pattern')->once()->with('hash', '[a-z0-9]+');
        });
    }

    /**
     * @test
     */
    public function it maps the uuid pattern() : void
    {
        $this->assertRoutePattern(function (Router $registrar) {
            $registrar->shouldReceive('pattern')->once()->with('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
        });
    }

    /**
     * @test
     */
    public function it maps the slug pattern() : void
    {
        $this->assertRoutePattern(function (Router $registrar) {
            $registrar->shouldReceive('pattern')->once()->with('slug', '[a-z0-9-]+');
        });
    }

    /**
     * @test
     */
    public function it maps the token pattern() : void
    {
        $this->assertRoutePattern(function (Router $registrar) {
            $registrar->shouldReceive('pattern')->once()->with('token', '[a-zA-Z0-9]{64}');
        });
    }

    /**
     * @test
     */
    public function it maps the domain pattern() : void
    {
        $this->assertRoutePattern(function (Router $registrar) {
            $registrar->shouldReceive('pattern')->once()->with('domain', '[a-z0-9.]+');
        });
    }

    /**
     * @param callable $assertion
     */
    protected function assertRoutePattern(callable $assertion) : void
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
