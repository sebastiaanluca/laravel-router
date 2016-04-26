<?php

namespace SebastiaanLuca\Router\Tests\Routers;

use SebastiaanLuca\Router\Routers\BootstrapRouter;
use SebastiaanLuca\Router\Tests\TestCase;

class BootstrapRouterTest extends TestCase
{
    
    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    protected function createBootstrapRouter()
    {
        return app(BootstrapRouter::class);
    }
    
    /**
     * Initialize the bootstrap router and get the registered patterns.
     */
    protected function setUpAndGetRoutePatterns()
    {
        $this->createBootstrapRouter();
        
        return app('router')->getPatterns();
    }
    
    
    
    public function testItMapsTheIdRoutePattern()
    {
        $this->assertArrayHasKey('id', $this->setUpAndGetRoutePatterns());
    }
    
    public function testItMapsTheHashRoutePattern()
    {
        $this->assertArrayHasKey('hash', $this->setUpAndGetRoutePatterns());
    }
    
    public function testItMapsTheUuidRoutePattern()
    {
        $this->assertArrayHasKey('uuid', $this->setUpAndGetRoutePatterns());
    }
    
    public function testItMapsTheSlugRoutePattern()
    {
        $this->assertArrayHasKey('slug', $this->setUpAndGetRoutePatterns());
    }
    
    public function testItMapsTheDomainRoutePattern()
    {
        $this->assertArrayHasKey('domain', $this->setUpAndGetRoutePatterns());
    }
    
}