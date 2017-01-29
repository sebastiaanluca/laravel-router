# Laravel Router

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sebastiaanluca/laravel-router.svg?style=flat-round)][link-packagist]
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-round)](LICENSE.md)
[![Build Status](https://travis-ci.org/sebastiaanluca/laravel-router.svg?style=flat-round)](https://travis-ci.org/sebastiaanluca/laravel-router)
[![Total Downloads](https://img.shields.io/packagist/dt/sebastiaanluca/laravel-router.svg?style=flat-round)][link-packagist]

[![Share this package on Twitter](https://img.shields.io/twitter/follow/sebastiaanluca.svg?style=social)](https://twitter.com/sebastiaanluca)
[![Share this package on Twitter](https://img.shields.io/twitter/url/http/shields.io.svg?style=social)](https://twitter.com/home?status=Check%20out%20this%20nifty%20way%20of%20organizing%20your%20%23Laravel%20routes!%20https%3A//github.com/sebastiaanluca/laravel-router%20via%20%40sebastiaanluca)

__An organized approach to handling routes in Laravel and Lumen.__ It also provides additional functionality on top of the default HTTP router.

So instead of those bulky `web.php` and `api.php` route files that are often too big, lacking any structure, and break Laravel structure conventions of separating everything in classes instead of regular PHP files, this package provides you with an easy-to-use system to separate route logic into __routers__ based on functionality.

## Table of contents

* [Laravel version](#laravel-version)
* [How to install](#how-to-install)
* [Usage](#usage)
    + [Setting up a router](#setting-up-a-router)
    + [Common route parameter patterns](#common-route-parameter-patterns)
    + [Full-domain routing](#full-domain-routing)
    + [Applying middleware using route names](#applying-middleware-using-route-names)
* [Change log](#change-log)
* [Testing](#testing)
* [Contributing](#contributing)
* [Security](#security)
* [Credits](#credits)
* [About](#about)
* [License](#license)

Table of contents generated with [markdown-toc](http://ecotrust-canada.github.io/markdown-toc/).

## Laravel version

Version 2 is targeted for use within a Laravel 5.3+ application. If you're looking for a version for 5.1 or 5.2, have a look at v1.

This package also requires at least PHP 7.0. Support for older versions can be found in previous package releases.

## How to install

Install the package through Composer by using the following command:

```bash
composer require sebastiaanluca/laravel-router
```

Add the service provider to the `providers` array in your `config/app.php` file:

```php
SebastiaanLuca\Router\RouterServiceProvider::class,
```

This will allow you to structure your routes into __routers__ by simply extending the base class and instantiating your router (anywhere).

If you want to register your routers in a single place (the HTTP kernel) or add middleware to routes using wildcards and so on, your application's HTTP kernel (usually found at `App\Http\Kernel` in your project) should extend the package's custom kernel instead of the default.

So just replace (at the top of the Kernel class):

```php
use Illuminate\Foundation\Http\Kernel as HttpKernel;
```

with the following line:

```php
use SebastiaanLuca\Router\Kernel as HttpKernel;
```

## Usage

### Setting up a router

The following is an example of a router. It can be placed anywhere you like, though I'd suggest grouping them under `App\Http\Routers`.

```php
<?php

namespace App\Http\Routers\Users;

use SebastiaanLuca\Router\Routers\Router;

class AuthRouter extends Router
{
    /**
     * Map the routes.
     */
    public function map()
    {
        $this->router->group(['middleware' => ['web', 'guest'], function () {
        
            $this->router->get('/users', function () {
            
                return view('users.welcome');
                
            });
            
        });
    }
}
```

The `map` method is where you should define your routes and is the *only* requirement when using a router. The Laravel routing instance is automatically resolved from the IoC container, so you can use any standard routing functionality you want with the additional functionality this package provides (see further down). Of course you can also use the `Route` facade.

__Remember__ that using this packages changes _nothing_ to the way you define your routes. It's just a way of organizing them. Optionally you can use the additional functionality it provides, but that's not a requirement.

To automatically have the package load your router and map its routes, add it to the `$routers` array in your application's kernel class:

```php
/**
 * The routers to automatically map.
 *
 * @var array
 */
protected $routers = [
    \App\Http\Routers\Users\UserRouter::class,
];
```

If you're not using the custom kernel, you can also register the router manually by just instantiating it (in a service provider for instance). The parent base router will automatically resolve all dependencies and call the `map` method on your router. Works great in packages!

```php
app(\App\Http\Routers\Users\UserRouter::class);
```

**Optionally**, you can define a namespace (variable) in your router and use it in your mapping. Feel free to delete this local variable if you favor writing the class name yourself.

Example using a predefined namespace:

```php
/**
 * The default controller namespace.
 *
 * @var string
 */
protected $namespace = 'App\Http\Controllers\Users';

/**
 * Map the routes.
 */
public function map()
{
    $this->router->group(['as' => 'public.', 'namespace' => $this->getNamespace()], function () {
        
        $this->router->get('/', ['as' => 'index', 'uses' => 'PublicController@index']);
        
    });
}
```

Example using the class (name) itself (personal preference); which PHPStorm for instance can automatically detect when refactoring:

```php
/**
 * Map the routes.
 */
public function map()
{
    $this->router->group(['as' => 'public.'], function () {
        
        $this->router->get('/', ['as' => 'index', 'uses' => PublicController::class . '@index']);
        
    });
}
```

### Common route parameter patterns

Laravel provides a convenient way to validate URL parameters using [patterns](https://laravel.com/docs/5.1/routing#route-parameters) in routes. This package provides a predefined set of such patterns so you don't have to repeatedly add them to each route or define them yourself. The following parameter patterns are currently supported:

- id (`\d+`)
- hash (`[a-z0-9]+`)
- uuid (`[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}`)
- slug (`[a-z0-9-]+`)
- token (`[a-zA-Z0-9]{100}`)

Forget about writing:

```php
Route::get('user/activations/{uuid}', function ($uuid) {
    //
})->where('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
```

Just use the `{uuid}` or any other pattern in your route:

```php
$this->router->get('user/activations/{uuid}', function ($uuid) {
    //
});
```


### Full-domain routing

Another great feature of Laravel is [sub-domain routing](https://laravel.com/docs/5.1/routing#route-groups) which allows you to handle multiple subdomains within a single Laravel project. The only caveat there is that it only does that and doesn't handle full domains. Laravel Router fixes that for you so you can direct multiple domains to a single Laravel project and handle them all at once.

Simply define a route group with the `{domain}` pattern and use it in your callback or controller:

```php
$this->router->group(['domain' => '{domain}'], function () {

    $this->router->get('user/{id}', function ($domain, $id) {
        return 'You\'re visiting from ' . $domain;
    });
    
});
```

### Applying middleware using route names

Laravel 4 had another awesome feature called [route filters](https://laravel.com/docs/4.2/routing#route-filters) that allowed you to apply a filter to a range of routes using their URL, optionally in combination with a wildcard. This was very useful when you had already defined your routes, but still wanted to apply a filter to them. Or when you wanted to apply a filter to the routes of a package, without extending or overriding anything.

Laravel Router allows you to use a variation on that by applying middleware to already defined routes using their names. Very practical when you want to separate functionality, apply middleware conditionally and anywhere you want, apply just one or a series of middleware, et cetera.

A very basic example to apply middleware to a single route:

```php
$this->router->registerNamedRouteMiddleware('admin.users.index', AdminMiddleware::class);
```

Apply middleware to a bunch of routes using a wildcard

```php
$this->router->registerNamedRouteMiddleware('admin.users.*', AdminMiddleware::class);
```

Handle multiple routes at once, even with wildcards:

```php
$this->router->registerNamedRouteMiddleware(['admin.users.index', 'admin.users.edit', 'admin.items.*', 'front.auth.*', 'front.index'], YourMiddleware::class);
```

Assign multiple middleware:

```php
$this->router->registerNamedRouteMiddleware('front.*', [DomainMiddleware::class, AuthMiddleware::class, OtherMiddleware::class]);
```

A combination of all:

```php
$this->router->registerNamedRouteMiddleware(['admin.users.index', 'admin.users.edit', 'admin.items.*', 'front.auth.*', 'front.index'], [DomainMiddleware::class, AuthMiddleware::class, OtherMiddleware::class]);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

Run `composer test` or `vendor/bin/phpunit`.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email [hello@sebastiaanluca.com][author-email] instead of using the issue tracker.

## Credits

- [Sebastiaan Luca][link-author]
- [All Contributors][link-contributors]

## About

My name is Sebastiaan and I'm a freelance back-end developer from Belgium specializing in building high-end, custom Laravel applications. Check out my [portfolio][author-portfolio] for more information and my other [packages](https://github.com/sebastiaanluca?tab=repositories) to kick-start your next project. Have a project that could use some guidance? Send me an e-mail at [hello@sebastiaanluca.com][author-email]!

## License

This package operates under the MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.

[link-packagist]: https://packagist.org/packages/sebastiaanluca/laravel-router
[link-contributors]: ../../contributors
[link-author]: https://github.com/sebastiaanluca
[author-portfolio]: http://www.sebastiaanluca.com
[author-email]: mailto:hello@sebastiaanluca.com
