<?php

namespace SebastiaanLuca\Router\Routers;

use Illuminate\Contracts\Routing\Registrar;

/**
 * The base class for routers.
 *
 * @package SebastiaanLuca\Router\Routers
 */
abstract class Router
{
    /**
     * The routing instance.
     *
     * @var \Illuminate\Contracts\Routing\Registrar|\Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Router constructor.
     *
     * @param \Illuminate\Contracts\Routing\Registrar|\Illuminate\Routing\Router $router
     */
    public function __construct(Registrar $router)
    {
        $this->router = $router;

        $this->map();
    }

    /**
     * Map the routes.
     *
     * @return void
     */
    abstract public function map() : void;
}
