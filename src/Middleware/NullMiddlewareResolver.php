<?php declare(strict_types=1);

namespace Moya\Http\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class NullMiddlewareResolver implements MiddlewareResolver
{
	public function resolveMiddleware(mixed $middleware): MiddlewareInterface|RequestHandlerInterface|null
	{
		if ($middleware instanceof MiddlewareInterface || $middleware instanceof RequestHandlerInterface) {
			return $middleware;
		}
		return null;
	}
}
