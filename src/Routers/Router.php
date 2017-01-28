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
     * @var \SebastiaanLuca\Router\ExtendedRouter|\Illuminate\Routing\Router
     */
    protected $router;

    /**
     * The Dingo API router.
     *
     * @var \Dingo\Api\Routing\Router
     */
    protected $api;

    /**
     * The default controller namespace.
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * Router constructor.
     *
     * @param \Illuminate\Contracts\Routing\Registrar $router
     */
    public function __construct(Registrar $router)
    {
        $this->router = $router;

        $this->setUpApiRouter();

        $this->map();
    }

    /**
     * Map the routes.
     */
    public abstract function map();

    /**
     * Get the default namespace with the suffix attached.
     *
     * @param string|null $suffix
     *
     * @return string
     */
    public function getNamespace($suffix = null) : string
    {
        if (! $suffix) {
            return $this->namespace;
        }

        return $this->namespace . '\\' . $suffix;
    }

    /**
     * Assign the API router if the Dingo API package is installed.
     */
    protected function setUpApiRouter()
    {
        if (class_exists('\Dingo\Api\Routing\Router')) {
            $this->api = app('\Dingo\Api\Routing\Router');
        }
    }
}
