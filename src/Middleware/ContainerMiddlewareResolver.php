<?php declare(strict_types=1);

namespace Stefna\Http\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class ContainerMiddlewareResolver implements MiddlewareResolver
{
	public function __construct(
		private readonly ContainerInterface $container,
		private readonly LoggerInterface $logger = new NullLogger(),
	) {}

	public function resolveMiddleware(null|object|string $middleware): MiddlewareInterface|RequestHandlerInterface|null
	{
		if (!$middleware) {
			return null;
		}
		if (is_string($middleware)) {
			if (!$this->container->has($middleware)) {
				$this->logger->error('Failed to find middleware "' . $middleware . '"', [
					'middleware' => $middleware,
				]);
				return null;
			}
			$middleware = $this->container->get($middleware);
		}

		if ($middleware instanceof MiddlewareInterface || $middleware instanceof RequestHandlerInterface) {
			return $middleware;
		}

		$this->logger->error('Failed to find valid middleware', [
			'type' => get_debug_type($middleware),
		]);
		return null;
	}
}
