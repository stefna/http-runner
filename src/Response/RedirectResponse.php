<?php declare(strict_types=1);

namespace Stefna\Http\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

final class RedirectResponse implements ResponseInterface
{
	use ResponseTrait;

	public function __construct(
		string|UriInterface $uri,
		Status $code = Status::Found,
	) {
		$this->statusCode = $code;

		$this->setHeaders([
			'Location' => (string)$uri,
		]);
	}
}
