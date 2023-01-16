<?php declare(strict_types=1);

namespace Stefna\Http\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewarePipeline
{
	public function shift(): null|string|MiddlewareInterface|RequestHandlerInterface;
}
