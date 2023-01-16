<?php declare(strict_types=1);

namespace Stefna\Http\Tests\Middleware\Fixture;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RespondingMiddleware implements MiddlewareInterface
{
	/**
	 * @param null|callable $requestCallback
	 */
	public function __construct(
		private readonly ResponseInterface $response,
		private $requestCallback = null,
	) {}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		if ($this->requestCallback) {
			($this->requestCallback)($request);
		}
		return $this->response;
	}
}
