<?php declare(strict_types=1);

namespace Stefna\Http\ResponseHandler;

use Stefna\Http\ResponseHandler\Exception\HeadersAlreadySentException;
use Psr\Http\Message\ResponseInterface;

final class HttpResponseHandler implements ResponseHandler
{
	public const PASSTHRU = 'passthru';

	private ResponseInterface $response;

	public function getResponse(): ResponseInterface
	{
		return $this->response;
	}

	public function handle(ResponseInterface $response): void
	{
		if (headers_sent()) {
			throw new HeadersAlreadySentException('Unable to send response. Headers already sent');
		}
		$this->response = $response;

		$version = $response->getProtocolVersion();
		$status = $response->getStatusCode();
		$phrase = $response->getReasonPhrase();

		header("HTTP/{$version} {$status} {$phrase}", true, $status);

		$doPassthru = false;

		foreach ($response->getHeaders() as $name => $values) {
			if ($name === self::PASSTHRU) {
				$doPassthru = true;
				continue;
			}
			$name = str_replace('-', ' ', $name);
			$name = ucwords($name);
			$name = str_replace(' ', '-', $name);
			foreach ($values as $value) {
				header("{$name}: {$value}", false);
			}
		}

		$stream = $response->getBody();

		if ($doPassthru) {
			// @phpstan-ignore-next-line - This can't be true at this point
			fpassthru($stream->detach());
		}
		elseif ($stream->isReadable()) {
			$stream->rewind();
			while (!$stream->eof()) {
				echo $stream->read(8192);
			}
		}
	}
}
