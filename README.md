# Laravel Router

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

__An organized approach to handling routes in Laravel and Lumen.__ Also provides additional functionality on top of the default HTTP router.

The intended use is to organize your routes into **routers** based on functionality. For instance admin, public, and user routes are separated into different classes instead of one long `routes.php` file or scattered throughout different PHP files. Or even worse, directly in your application's HTTP kernel class.

## Install

Install the package through Composer by using the following command:

``` bash
$ composer require sebastiaanluca/laravel-router
```

Add the service provider to the `providers` array in your `config/app.php` file:

``` php
SebastiaanLuca\Router\RouterServiceProvider::class,
```

The following step is __optional__, but encouraged to simplify setup and allow you to use some neat functionality this package offers.

Your application's HTTP kernel (usually found at `app\Http\Kernel` in your project) should extend the package's custom kernel instead of the default. So just replace `use Illuminate\Foundation\Http\Kernel as HttpKernel;` at the top of the class with the following line:

``` php
use SebastiaanLuca\Router\Kernel as HttpKernel;
```

## Usage

### Setting up routers

To get started, you have to create a router that will contain your routes. The following is an example of such a router. It can be placed anywhere you like, though I'd suggest grouping them under `App\Http\Routers`.

The `map` method is where you should define your routes and is the *only* requirement when using a router. The Laravel routing instance is automatically resolved from the IoC container, so you can use any standard routing functionality you want with the additional functionality this package provides (see further down).

``` php
<?php

namespace App\Http\Routers;

use SebastiaanLuca\Router\Routers\BaseRouter;
use SebastiaanLuca\Router\Routers\RouterInterface;

class PublicRouter extends BaseRouter implements RouterInterface
{
    
    /**
     * Map the routes.
     */
    public function map()
    {
        $this->router->get('/', function () {
            return 'Congratulations!';
        });
    }
    
}
```

To automatically have the package load your router and map its routes, add it to the `$routers` array in your application's kernel class (which should extend `SebastiaanLuca\Router\Kernel`):

``` php
/**
 * The routers to automatically map.
 *
 * @var array
 */
protected $routers = [
    PublicRouter::class,
];
```

If you're not using the custom kernel, you can also register the router manually by just instantiating it. This can be useful when making use of it in another package.

``` php
app(PublicRouter::class);
```

**Optionally**, you can define a namespace per router and use it with `$this->getNamespace()` in your mapping. Feel free to delete this local variable if you favor using something like `'uses' => PublicController::class . '@index'` which i.e. PHPStorm can detect when refactoring.

Example using a predefined namespace:

``` php
    
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

Example using the class (name) itself (personal preference):

``` php
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

Laravel provides a convenient way to validate URL parameters using [patterns](https://laravel.com/docs/5.1/routing#route-parameters) in routes:

``` php
Route::get('user/{id}/{name}', function ($id, $name) {
    //
})->where(['id' => '[0-9]+', 'name' => '[a-z]+']);
```

Such pattern can also be globally defined in a service provider, for instance for an ID:

```php
Route::pattern('id', '[0-9]+');
```

This package provides a predefined set of such patterns so you don't have to repeatedly add them to each route or define them yourself. The following parameter patterns are currently supported:

- id
- hash
- uuid
- slug

Forget about writing:

``` php
Route::get('user/activations/{uuid}', function ($uuid) {
    //
})->where('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
```

Just use the `{uuid}` or any other pattern in your route:

``` php
$this->router->get('user/activations/{uuid}', function ($uuid) {
    //
});
```


### Full-domain routing (TODO)

Another great feature of Laravel is [sub-domain routing](https://laravel.com/docs/5.1/routing#route-groups) which allows you to handle multiple subdomains within a single Laravel project. The only caveat there is that it only does that and doesn't handle full domains. Laravel Router fixes that for you so you can direct multiple domains to a single Laravel project and handle them all at once.

Simply define a route group with the `{domain}` pattern and use it in your callback or controller:

``` php
$this->router->group(['domain' => '{domain}'], function () {

    $this->router->get('user/{id}', function ($domain, $id) {
        //
    });
    
});
```

### Applying middleware using route names

Laravel 4 had another awesome feature called [route filters](https://laravel.com/docs/4.2/routing#route-filters) that allowed you to apply a filter to a range of routes using their URL, optionally in combination with a wildcard. This was very useful when you had already defined your routes, but still wanted to apply a filter to them. Or when you wanted to apply a filter to the routes of a package, without extending or overriding anything.

Laravel Router allows you to use a variation on that by applying middleware to already defined routes using their names. Very practical when you want to separate functionality, apply middleware conditionally and anywhere you want, apply just one or a series of middleware, et cetera.

A very basic example to apply middleware to a single route:

``` php
$this->router->registerNamedRouteMiddleware('admin.users.index', AdminMiddleware::class);
```

Apply middleware to a bunch of routes using a wildcard

``` php
$this->router->registerNamedRouteMiddleware('admin.users.*', AdminMiddleware::class);
```

Handle multiple routes at once, even with wildcards:

``` php
$this->router->registerNamedRouteMiddleware(['admin.users.index', 'admin.users.edit', 'admin.items.*', 'front.auth.*', 'front.index'], YourMiddleware::class);
```

Assign multiple middleware:

``` php
$this->router->registerNamedRouteMiddleware('front.*', [DomainMiddleware::class, AuthMiddleware::class, OtherMiddleware::class]);
```

A combination of all:

``` php
$this->router->registerNamedRouteMiddleware(['admin.users.index', 'admin.users.edit', 'admin.items.*', 'front.auth.*', 'front.index'], [DomainMiddleware::class, AuthMiddleware::class, OtherMiddleware::class]);
```

### Notes

In addition to using `$this->router` in your routers, you can also use the traditional `Route` facade anywhere in your application and still benefit from the extra functionality such as wildcard middleware and predefined route patterns.

``` php
Route::registerNamedRouteMiddleware('admin.users.*', AdminContextMiddleware::class);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

None yet.

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

[ico-version]: https://img.shields.io/packagist/v/sebastiaanluca/laravel-router.svg?style=flat-round
[ico-downloads]: https://img.shields.io/packagist/dt/sebastiaanluca/laravel-router.svg?style=flat-round
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-round

[link-packagist]: https://packagist.org/packages/sebastiaanluca/laravel-router
[link-downloads]: https://packagist.org/packages/sebastiaanluca/laravel-router
[link-contributors]: ../../contributors
[link-author]: https://github.com/sebastiaanluca
[author-portfolio]: http://www.sebastiaanluca.com
[author-email]: mailto:hello@sebastiaanluca.com