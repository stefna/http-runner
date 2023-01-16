<?php declare(strict_types=1);

namespace Stefna\Http\Response;

use Nyholm\Psr7\MessageTrait;

trait ResponseTrait
{
	use MessageTrait;

	protected Status $statusCode = Status::NoContent;

	public function getStatusCode()
	{
		return $this->statusCode->value;
	}

	public function withStatus($code, $reasonPhrase = '')
	{
		$clone = clone $this;
		$clone->statusCode = Status::from($code);
		return $clone;
	}

	public function getReasonPhrase()
	{
		return $this->statusCode->getPhrase();
	}

	/**
	 * Sets the provided Content-Type, if none is already present.
	 *
	 * @param string $contentType
	 */
	private function setContentTypeHeaderIfNotExists(string $contentType): void
	{
		if (!$this->hasHeader('content-type')) {
			$this->headerNames['content-type'] = 'Content-Type';
			$this->headers[$this->headerNames['content-type']] = [$contentType];
		}
	}
}
