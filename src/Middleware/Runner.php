<?php declare(strict_types=1);

namespace Stefna\Http\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Runner implements RequestHandlerInterface
{
	public function __construct(
		private readonly MiddlewarePipeline $middlewarePipeline,
		private readonly ResponseFactoryInterface $responseFactory,
		private readonly MiddlewareResolver $middlewareResolver = new NullMiddlewareResolver(),
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		$middleware = $this->middlewareResolver->resolveMiddleware($this->middlewarePipeline->shift());

		// It there's no middlewares just return 404
		if ($middleware === null) {
			return $this->responseFactory->createResponse(404);
		}

		if ($middleware instanceof MiddlewareInterface) {
			return $middleware->process($request, clone $this);
		}

		return $middleware->handle($request);
	}
}
