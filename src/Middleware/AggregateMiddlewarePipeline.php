<?php declare(strict_types=1);

namespace Moya\Http\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AggregateMiddlewarePipeline implements MiddlewarePipeline
{
	/** @var MiddlewarePipeline[] */
	private array $pipelines;

	private ?MiddlewarePipeline $currentPipeline;

	public function __construct(MiddlewarePipeline ...$middlewarePipeline)
	{
		$this->pipelines = $middlewarePipeline;
	}

	public function shift(): null|string|MiddlewareInterface|RequestHandlerInterface
	{
		if (!isset($this->currentPipeline)) {
			$this->currentPipeline = array_shift($this->pipelines);
		}
		if ($this->currentPipeline === null) {
			return null;
		}

		$middleware = $this->currentPipeline->shift();
		if ($middleware) {
			return $middleware;
		}
		$this->currentPipeline = array_shift($this->pipelines);
		return $this->shift();
	}
}
