<?php

namespace SebastiaanLuca\Router\Routers;

use Illuminate\Contracts\Routing\Registrar as RegistrarContract;

/**
 * Class BaseRouter
 *
 * The base class every router should extend.
 *
 * @package SebastiaanLuca\Router\Routers
 */
abstract class BaseRouter implements RouterInterface
{
    
    /**
     * The routing instance.
     *
     * @var \SebastiaanLuca\Router\ExtendedRouter|\Illuminate\Routing\Router
     */
    protected $router;
    
    /**
     * The default controller namespace.
     *
     * @var string
     */
    protected $namespace = '';
    
    /**
     * BaseRouter constructor.
     *
     * @param \Illuminate\Contracts\Routing\Registrar $router
     */
    public function __construct(RegistrarContract $router)
    {
        $this->router = $router;
        
        $this->map();
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
