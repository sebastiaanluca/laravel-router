<?php

namespace SebastiaanLuca\Router\Routers;

use Illuminate\Contracts\Routing\Registrar as RegistrarContract;

/**
 * Class Router
 *
 * The base class every router should extend.
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
    public function __construct(RegistrarContract $router)
    {
        $this->router = $router;
        
        $this->setUpApiRouter();
        
        $this->map();
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
    
    
    
    /**
     * Get the default namespace with the suffix attached.
     *
     * @param string|null $suffix
     *
     * @return string
     */
    public function getNamespace($suffix = null)
    {
        if (! $suffix) {
            return $this->namespace;
        }
        
        return $this->namespace . '\\' . $suffix;
    }
    
    
    
    /**
     * Map the routes.
     */
    public abstract function map();
    
}
