<?php

namespace SebastiaanLuca\Router\Tests\Unit;

use Illuminate\Contracts\Routing\Registrar;
use SebastiaanLuca\Router\Routers\RegisterRoutePatterns;
use SebastiaanLuca\Router\Tests\TestCase;

class BootstrapRouterTest extends TestCase
{
    // TODO: split
    public function testItMapsTheIdRoutePattern()
    {
        $registrar = $this->mock(Registrar::class);

        $registrar->shouldReceive('pattern')->once()->with('id', '\d+');
        $registrar->shouldReceive('pattern')->once()->with('hash', '[a-z0-9]+');
        $registrar->shouldReceive('pattern')->once()->with('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
        $registrar->shouldReceive('pattern')->once()->with('slug', '[a-z0-9-]+');
        $registrar->shouldReceive('pattern')->once()->with('token', '[a-zA-Z0-9]{100}');
        $registrar->shouldReceive('pattern')->once()->with('domain', '[a-z0-9.]+');

        $this->createBootstrapRouter($registrar);
    }

    /**
     * @param \Illuminate\Contracts\Routing\Registrar $registrar
     *
     * @return \SebastiaanLuca\Router\Routers\RegisterRoutePatterns
     */
    protected function createBootstrapRouter(Registrar $registrar) : RegisterRoutePatterns
    {
        return new RegisterRoutePatterns($registrar);
    }
}
