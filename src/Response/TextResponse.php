<?php declare(strict_types=1);

namespace Stefna\Http\Response;

use Nyholm\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;

final class TextResponse implements ResponseInterface
{
	use ResponseTrait;

	public function __construct(
		string|\Stringable $text,
		Status $code = Status::Ok,
	) {
		$this->statusCode = $code;
		$this->stream = Stream::create((string)$text);
		$this->setContentTypeHeaderIfNotExists('text/plain; charset=UTF-8');
	}
}
