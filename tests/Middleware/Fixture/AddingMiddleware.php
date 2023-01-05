<?php declare(strict_types=1);

namespace Moya\Http\Tests\Middleware\Fixture;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AddingMiddleware implements MiddlewareInterface
{
	public function __construct(
		private readonly int $add = 1,
	) {}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		return $handler->handle($request->withAttribute('total', $request->getAttribute('total') + $this->add));
	}
}
