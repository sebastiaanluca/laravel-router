<?php

namespace SebastiaanLuca\Router\Tests;

use Illuminate\Foundation\Bootstrap\BootProviders;
use SebastiaanLuca\Router\ExtendedRouter;

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