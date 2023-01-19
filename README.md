# Http

This package provides a psr-15 implementation with helpers

## Requirements

PHP 8.2 or higher.

## Installation

```bash
composer require stefna/http-runner
```

## Getting started

```php
<?php

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stefna\Http\Middleware\ContainerMiddlewareResolver;
use Stefna\Http\Middleware\NullMiddlewareResolver;
use Stefna\Http\Middleware\Runner;
use Stefna\Http\Middleware\SimpleMiddlewarePipeline;
use Stefna\Http\Request\ServerRequestMarshal;
use Stefna\Http\ResponseHandler\HttpResponseHandler;

/** @var ContainerInterface $container */
/** @var ResponseFactoryInterface $responseFactory */

$middlewares = new SimpleMiddlewarePipeline(
	new CrashMiddleware(),
	new SessionMiddleware(),
	new RouterMiddleware(),
	new RouteDispatchMiddleware(),
);

// no resolving of middlewares all middlewares need to be instantiated earlier
$middlewareResolver = new NullMiddlewareResolver();
// or if middleware is a string look in container for it and lazy create it when needed
$middlewareResolver = new ContainerMiddlewareResolver($container);

$runner = new Runner(
	$middlewares,
	$responseFactory,
	$middlewareResolver,
);

// boot request
/** @var ServerRequestInterface $request */
$request = (new ServerRequestMarshal())->marshal($_SERVER);

// dispatch middlewares and get a response back
$response = $runner->handle($request);

// send response
(new HttpResponseHandler())->handle($response);
```

## Contribute

We are always happy to receive bug/security reports and bug/security fixes

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
