<?php

namespace SebastiaanLuca\Router\Exceptions;

use RuntimeException;

class RouterException extends RuntimeException
{
    /**
     * @return static
     */
    public static function missingKernelRouters()
    {
        return new static('HTTP kernel is missing the $routers variable.');
    }
}
