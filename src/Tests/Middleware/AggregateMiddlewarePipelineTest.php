<?php declare(strict_types=1);

namespace Moya\Http\Tests\Middleware;

use Moya\Http\Middleware\AggregateMiddlewarePipeline;
use Moya\Http\Middleware\SimpleMiddlewarePipeline;
use Moya\Http\Tests\Middleware\Fixture\AddingMiddleware;
use PHPUnit\Framework\TestCase;

final class AggregateMiddlewarePipelineTest extends TestCase
{
	public function testExecuteOrder(): void
	{
		$m1 = new AddingMiddleware(1);
		$pipeline1 = new SimpleMiddlewarePipeline($m1);
		$m2 = new AddingMiddleware(2);
		$pipeline2 = new SimpleMiddlewarePipeline($m2);

		$aggregatePipeline = new AggregateMiddlewarePipeline(
			$pipeline1,
			$pipeline2,
		);

		$this->assertSame($m1, $aggregatePipeline->shift());
		$this->assertSame($m2, $aggregatePipeline->shift());
		$this->assertNull($aggregatePipeline->shift());
	}
}
