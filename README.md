# Laravel Router

[![Latest stable release][version-badge]][link-packagist]
[![Software license][license-badge]](LICENSE.md)
[![Build status][travis-badge]][link-travis]
[![Total downloads][downloads-badge]][link-packagist]

[![Read my blog][blog-link-badge]][link-blog]
[![View my other packages and projects][packages-link-badge]][link-packages]
[![Follow @sebastiaanluca on Twitter][twitter-profile-badge]][link-twitter]
[![Share this package on Twitter][twitter-share-badge]][link-twitter-share]

__An organized approach to handling routes in Laravel.__

This package provides you with an easy-to-use system to separate route logic into __routers__ based on functionality while also providing additional functionality. A replacement for those bulky `web.php` and `api.php` route files that are often lacking any structure and break Laravel structure conventions of separating everything in classes instead of regular PHP files.

Do note that it *changes nothing to the way you define your routes*. It's just a way of organizing them. Optionally you can use the additional functionality it provides, but that's not a requirement.

## Table of contents

* [Requirements](#requirements)
* [How to install](#how-to-install)
    + [Laravel 5.5](#laravel-55)
    + [Laravel 5.4](#laravel-54)
    + [Further optional setup](#further-optional-setup)
* [How to use](#how-to-use)
    + [Creating a router](#creating-a-router)
    + [Registering the router](#registering-the-router)
        - [Manually registering the router](#manually-registering-the-router)
* [Optional features](#optional-features)
    + [Common route parameter patterns](#common-route-parameter-patterns)
    + [Full-domain routing](#full-domain-routing)
* [License](#license)
* [Change log](#change-log)
* [Testing](#testing)
* [Contributing](#contributing)
* [Security](#security)
* [Credits](#credits)
* [About](#about)

Table of contents generated with [markdown-toc](http://ecotrust-canada.github.io/markdown-toc/).

## Requirements

- PHP 7 or higher
- Laravel 5.4 or higher

Looking for support for PHP 5.x or Laravel 5.3 and earlier? Try out any of the previous package versions.

## How to install

### Laravel 5.5

From Laravel 5.5 and onwards, this package supports auto-discovery. Just add the package to your project using composer and you're good to go!

```bash
composer require sebastiaanluca/laravel-router
```

### Laravel 5.4

Install the package through Composer by using the following command:

```bash
composer require sebastiaanluca/laravel-router
```

Add the service provider to the `providers` array in your `config/app.php` file:

```php
'providers' => [

    SebastiaanLuca\Router\RouterServiceProvider::class,

]
```

### Further optional setup

If you want to be able to register your routers **in a single place**, add the `RegistersRouters` trait to your HTTP kernel (found at `App\Http\Kernel`):

```php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use SebastiaanLuca\Router\Kernel\RegistersRouters;

class Kernel extends HttpKernel
{
    use RegistersRouters;
}
```

## How to use

### Creating a router

The following is an example of a router. It can be placed anywhere you like, though I'd suggest grouping them in the `App\Http\Routers` directory.

```php
<?php

namespace App\Http\Routers;

use SebastiaanLuca\Router\Routers\Router;

class UserRouter extends Router
{
    /**
     * Map the routes.
     */
    public function map()
    {
        $this->router->group(['middleware' => ['web', 'guest']], function () {

            $this->router->get('/users', function () {

                return view('users.index');

            });

        });
    }
}
```

The `map` method is where you should define your routes and is the *only* requirement when using a router. The Laravel routing instance is automatically resolved from the IoC container, so you can use any standard routing functionality you want. Of course you can also use the `Route` facade.

### Registering the router

To automatically have the framework load your router and map its routes, [add the trait](#further-optional-setup) and add the router to the `$routers` array in your application's HTTP kernel class:

```php
/**
 * The application routers to automatically boot.
 *
 * @var array
 */
protected $routers = [
    \App\Http\Routers\UserRouter::class,
];
```

#### Manually registering the router

If you don't want to or can't add the trait to the kernel, you can also register the router manually by just instantiating it (in a service provider for instance). The parent base router will automatically resolve all dependencies and call the `map` method on your router.

```php
app(\App\Http\Routers\UserRouter::class);
```

Especially useful in packages!

## Optional features

To use the following optional features, register the `RegisterRoutePatterns` class:

```php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use SebastiaanLuca\Router\Kernel\RegistersRouters;

class Kernel extends HttpKernel
{
    use RegistersRouters;

    /**
     * The application routers to automatically boot.
     *
     * @var array
     */
    protected $routers = [
        \SebastiaanLuca\Router\Routers\RegisterRoutePatterns::class,
    ];
}
```

### Common route parameter patterns

Laravel provides a convenient way to validate URL parameters using [patterns](https://laravel.com/docs/5.1/routing#route-parameters) in routes. This package provides a predefined set of such patterns so you don't have to repeatedly add them to each route or define them yourself. The following parameter patterns are currently included:

- id (`\d+`)
- hash (`[a-z0-9]+`)
- uuid (`[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}`)
- slug (`[a-z0-9-]+`)
- token (`[a-zA-Z0-9]{100}`)

So forget about writing:

```php
Route::get('user/activations/{uuid}', function ($uuid) {
    return view('users.activations.show');
})->where('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
```

Just use the `{uuid}` or any other pattern in your route:

```php
$this->router->get('user/activations/{uuid}', function ($uuid) {
    return view('users.activations.show');
});
```

### Full-domain routing

Another great feature of Laravel is [sub-domain routing](https://laravel.com/docs/5.1/routing#route-groups) which allows you to handle multiple subdomains within a single Laravel project. The only caveat there is that it only does that and doesn't handle full domains.

Laravel Router fixes that for you so you can direct multiple domains to a single Laravel project and handle them all at once. Simply define a route group with the `{domain}` pattern and use it in your callback or controller:

```php
$this->router->group(['domain' => '{domain}'], function () {

    $this->router->get('user/{id}', function ($domain, $id) {
        return 'You\'re visiting from ' . $domain;
    });
    
});
```

## License

This package operates under the MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer install
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email [hello@sebastiaanluca.com][link-author-email] instead of using the issue tracker.

## Credits

- [Sebastiaan Luca][link-github-profile]
- [All Contributors][link-contributors]

## About

My name is Sebastiaan and I'm a freelance Laravel developer specializing in building custom Laravel applications. Check out my [portfolio][link-portfolio] for more information, [my blog][link-blog] for the latest tips and tricks, and my other [packages][link-github-repositories] to kick-start your next project.

Have a project that could use some guidance? Send me an e-mail at [hello@sebastiaanluca.com][link-author-email]!

[version-badge]: https://poser.pugx.org/sebastiaanluca/laravel-router/version
[license-badge]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[travis-badge]: https://img.shields.io/travis/sebastiaanluca/laravel-router/master.svg
[downloads-badge]: https://img.shields.io/packagist/dt/sebastiaanluca/laravel-router.svg

[blog-link-badge]: https://img.shields.io/badge/link-blog-lightgrey.svg
[packages-link-badge]: https://img.shields.io/badge/link-other_packages-lightgrey.svg
[twitter-profile-badge]: https://img.shields.io/twitter/follow/sebastiaanluca.svg?style=social
[twitter-share-badge]: https://img.shields.io/twitter/url/http/shields.io.svg?style=social

[link-packagist]: https://packagist.org/packages/sebastiaanluca/laravel-router
[link-travis]: https://travis-ci.org/sebastiaanluca/laravel-router
[link-contributors]: ../../contributors

[link-portfolio]: https://www.sebastiaanluca.com
[link-blog]: https://blog.sebastiaanluca.com
[link-packages]: https://github.com/sebastiaanluca?tab=link-github-repositories
[link-twitter]: https://twitter.com/sebastiaanluca
[link-twitter-share]: https://twitter.com/home?status=Check%20out%20this%20nifty%20way%20of%20organizing%20your%20%23Laravel%20routes!%20https%3A//github.com/sebastiaanluca/laravel-router%20via%20%40sebastiaanluca
[link-github-profile]: https://github.com/sebastiaanluca
[link-github-repositories]: https://github.com/sebastiaanluca?tab=link-github-repositories
[link-author-email]: mailto:hello@sebastiaanluca.com
