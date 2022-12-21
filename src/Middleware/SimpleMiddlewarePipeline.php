<?php declare(strict_types=1);

namespace Moya\Http\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SimpleMiddlewarePipeline implements MiddlewarePipeline
{
	/** @var array<array-key, string|MiddlewareInterface|RequestHandlerInterface> */
	private array $middlewares;

	public function __construct(string|MiddlewareInterface|RequestHandlerInterface ...$middlewares)
	{
		$this->middlewares = $middlewares;
	}

	public function shift(): null|string|MiddlewareInterface|RequestHandlerInterface
	{
		return array_shift($this->middlewares);
	}
}
