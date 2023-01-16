<?php declare(strict_types=1);

namespace Stefna\Http\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

final class EmptyResponse implements ResponseInterface
{
	use ResponseTrait;

	public function __construct(
		Status $code = Status::NoContent,
	) {
		$this->statusCode = $code;
	}
}
