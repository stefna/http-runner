<?php declare(strict_types=1);

namespace Moya\Http\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareResolver
{
	public function resolveMiddleware(null|string|object $middleware): MiddlewareInterface|RequestHandlerInterface|null;
}
