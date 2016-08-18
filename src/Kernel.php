<?php

namespace SebastiaanLuca\Router;

use Illuminate\Contracts\Http\Kernel as KernelContract;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Class Kernel
 *
 * The extended HTTP kernel which should be extended by the application's kernel.
 *
 * @package SebastiaanLuca\Router
 */
class Kernel extends HttpKernel implements KernelContract
{
    /**
     * The routers to automatically map.
     *
     * @var array
     */
    protected $routers = [
        //
    ];
    
    /**
     * Get the route dispatcher callback.
     *
     * @return \Closure
     */
    protected function dispatchToRouter()
    {
        // Reconstruct the kernel and reset the class' router to use our new
        // extended router which got instantiated and bound into the IoC after
        // the default router got set up and bound. This might look kinda odd,
        // but poses no direct consequences.
        parent::__construct($this->app, $this->app->make('router'));
        
        // Continue as normal
        return parent::dispatchToRouter();
    }
    
    /**
     * Get the user-defined routers.
     *
     * @return array
     */
    public function getRouters()
    {
        return $this->routers;
    }
}
