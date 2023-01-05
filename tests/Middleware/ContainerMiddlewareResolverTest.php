<?php declare(strict_types=1);

namespace Moya\Http\Tests\Middleware;

use Moya\DependencyInjection\Container;
use Moya\DependencyInjection\Definition\DefinitionArray;
use Moya\Http\Middleware\ContainerMiddlewareResolver;
use Moya\Http\Tests\Middleware\Fixture\AddingMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Log\LoggerInterface;

final class ContainerMiddlewareResolverTest extends TestCase
{
	public function testMissingInContainer(): void
	{
		$container = $this->createMock(ContainerInterface::class);
		$container->expects($this->once())->method('has')->willReturn(false);
		$container->expects($this->never())->method('get');
		$logger = $this->createMock(LoggerInterface::class);
		$logger->expects($this->once())->method('error');
		$resolver = new ContainerMiddlewareResolver($container, $logger);
		$this->assertNull($resolver->resolveMiddleware(AddingMiddleware::class));
	}

	public function testFoundInContainer(): void
	{
		$middleware = new AddingMiddleware();
		$container = $this->createMock(ContainerInterface::class);
		$container->expects($this->once())->method('has')->willReturn(true);
		$container->expects($this->once())->method('get')->willReturn($middleware);
		$resolver = new ContainerMiddlewareResolver($container);
		$this->assertSame($middleware, $resolver->resolveMiddleware(AddingMiddleware::class));
	}

	public function testPassingNullToResolver(): void
	{
		$resolver = new ContainerMiddlewareResolver($this->createMock(ContainerInterface::class));
		$this->assertNull($resolver->resolveMiddleware(null));
	}

	public function testPassingObjectToResolverDontTouchContainer(): void
	{
		$container = $this->createMock(ContainerInterface::class);
		$container->expects($this->never())->method('has');
		$container->expects($this->never())->method('get');
		$middleware = $this->createMock(MiddlewareInterface::class);
		$resolver = new ContainerMiddlewareResolver($container);

		$resolvedMiddleware = $resolver->resolveMiddleware($middleware);
		$this->assertSame($middleware, $resolvedMiddleware);
	}

	public function testPassingInvalidObjectReturnNull(): void
	{
		$logger = $this->createMock(LoggerInterface::class);
		$logger->expects($this->once())->method('error');
		$resolver = new ContainerMiddlewareResolver(
			$this->createMock(ContainerInterface::class),
			$logger,
		);

		$this->assertNull($resolver->resolveMiddleware(new \DateTimeImmutable()));
	}
}
