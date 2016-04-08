<?php

namespace SebastiaanLuca\Router\Tests\Functional;

use SebastiaanLuca\Router\ExtendedRouter;
use SebastiaanLuca\Router\Tests\TestCase;

class ExtendedRouterTest extends TestCase
{
    
    public function testDefaultRouterGotSwappedWithExtendedRouter()
    {
        $this->assertInstanceOf(ExtendedRouter::class, app('router'));
    }
    
    public function testRouteFacadeGotSwappedWithExtendedRouter()
    {
        $this->assertInstanceOf(ExtendedRouter::class, \Route::getFacadeRoot());
    }
    
}