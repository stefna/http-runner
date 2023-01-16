<?php declare(strict_types=1);

namespace Stefna\Http\Tests\Middleware;

use Stefna\Http\Middleware\MiddlewarePipeline;
use Stefna\Http\Middleware\Runner;
use Stefna\Http\Middleware\SimpleMiddlewarePipeline;
use Stefna\Http\Tests\Middleware\Fixture\AddingMiddleware;
use Stefna\Http\Tests\Middleware\Fixture\RespondingMiddleware;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RunnerTest extends TestCase
{
	public function testDefaultResponseIs404(): void
	{
		$runner = new Runner($this->createMock(MiddlewarePipeline::class));

		$response = $runner->handle($this->createMock(ServerRequestInterface::class));

		$this->assertSame(404, $response->getStatusCode());
	}

	public function testMiddlewareStack(): void
	{
		$request = new ServerRequest('GET', '');
		$handler = new class implements RequestHandlerInterface {
			public function handle(ServerRequestInterface $request): ResponseInterface
			{
				RunnerTest::assertSame(3, $request->getAttribute('total'));

				return new Response(200);
			}
		};

		$pipeline = new SimpleMiddlewarePipeline(
			new AddingMiddleware(1),
			new AddingMiddleware(2),
			$handler
		);

		$runner = new Runner($pipeline);
		$runner->handle($request);
	}

	public function testMiddlewareEarlyReturn(): void
	{
		$request = new ServerRequest('GET', '');
		$response = new Response(202);

		$neverMiddleware = $this->createMock(MiddlewareInterface::class);
		$neverMiddleware->expects($this->never())->method('process');
		$pipeline = new SimpleMiddlewarePipeline(
			new AddingMiddleware(1),
			new RespondingMiddleware(
				$response,
				fn (ServerRequestInterface $request) => $this->assertSame(1, $request->getAttribute('total')),
			),
			$neverMiddleware,
		);

		$runner = new Runner($pipeline);
		$this->assertSame($response, $runner->handle($request));
	}
}
