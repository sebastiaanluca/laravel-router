<?php

namespace SebastiaanLuca\Router\Routers;

use SebastiaanLuca\Router\ExtendedRouter;

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
     * @param \SebastiaanLuca\Router\ExtendedRouter $router
     */
    public function __construct(ExtendedRouter $router)
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
