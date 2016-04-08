<?php

namespace SebastiaanLuca\Router\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use SebastiaanLuca\Router\RouterServiceProvider;

class TestCase extends OrchestraTestCase
{
    
    protected function getPackageProviders($app)
    {
        return [RouterServiceProvider::class];
    }
    
}
