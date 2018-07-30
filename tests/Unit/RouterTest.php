<?php

namespace SebastiaanLuca\Router\Tests\Unit;

use Illuminate\Contracts\Routing\Registrar;
use SebastiaanLuca\Router\Routers\Router;
use SebastiaanLuca\Router\Tests\TestCase;

class RouterTest extends TestCase
{
    /**
     * @test
     */
    public function it maps routes() : void
    {
        $registrar = $this->mock(Registrar::class);

        $registrar->shouldReceive('get')->twice()->with('/', ['as' => 'test']);

        $router = $this->createRouter($registrar);

        $router->map();
    }

    /**
     * @test
     */
    public function it automatically maps routes on instantiation() : void
    {
        $registrar = $this->mock(Registrar::class);

        $registrar->shouldReceive('get')->once()->with('/', ['as' => 'test']);

        $this->createRouter($registrar);
    }

    /**
     * @param \Illuminate\Contracts\Routing\Registrar|null $registrar
     *
     * @return \SebastiaanLuca\Router\Routers\Router
     */
    protected function createRouter($registrar = null) : Router
    {
        if (! $registrar) {
            $registrar = app(Registrar::class);
        }

        return new class($registrar) extends Router
        {
            /**
             * Map the routes.
             */
            public function map() : void
            {
                $this->router->get('/', ['as' => 'test']);
            }
        };
    }
}
