<?php declare(strict_types=1);

namespace Stefna\Http\Response;

use Nyholm\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;

final class HtmlResponse implements ResponseInterface
{
	use ResponseTrait;

	public function __construct(
		string|\Stringable $html,
		Status $code = Status::Ok,
	) {
		$this->statusCode = $code;
		$this->stream = Stream::create((string)$html);
		$this->setContentTypeHeaderIfNotExists('text/html; charset=UTF-8');
	}
}
