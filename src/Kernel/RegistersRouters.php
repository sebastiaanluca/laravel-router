<?php

declare(strict_types=1);

namespace SebastiaanLuca\Router\Kernel;

use SebastiaanLuca\Router\Exceptions\RouterException;

trait RegistersRouters
{
    /**
     * Get the registered kernel routers.
     *
     * @return array
     *
     * @throws \SebastiaanLuca\Router\Exceptions\RouterException
     */
    public function getRouters() : array
    {
        if (! isset($this->routers)) {
            throw RouterException::missingKernelRouters();
        }

        return $this->routers;
    }

    /**
     * Enable all registered kernel routers.
     *
     * @return void
     */
    public function bootRouters() : void
    {
        // Here we just need to instantiate each router as
        // they handle the mapping themselves
        foreach ($this->getRouters() as $router) {
            app($router);
        }
    }
}
