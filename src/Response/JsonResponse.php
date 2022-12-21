<?php declare(strict_types=1);

namespace Moya\Http\Response;

use InvalidArgumentException;
use JsonException;
use Nyholm\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;

final class JsonResponse implements ResponseInterface
{
	use ResponseTrait;

	public const DEFAULT_OPTIONS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

	public function __construct(
		mixed $data,
		Status $code = Status::Ok,
		int $encodingOptions = self::DEFAULT_OPTIONS,
	) {
		$this->statusCode = $code;
		$this->stream = Stream::create($this->encode($data, $encodingOptions));
		$this->setContentTypeHeaderIfNotExists('application/json; charset=UTF-8');
	}

	public function withJsonData(mixed $data, int $encodingOptions = self::DEFAULT_OPTIONS): self
	{
		return $this->withBody(Stream::create($this->encode($data, $encodingOptions)));
	}

	private function encode(mixed $data, int $encodingOptions): string
	{
		if (is_resource($data)) {
			throw new InvalidArgumentException('Resources cannot be encoded in JSON.');
		}

		try {
			return json_encode($data, $encodingOptions | JSON_THROW_ON_ERROR);
		} catch (JsonException $e) {
			throw new InvalidArgumentException(
				sprintf('Unable to encode data to JSON: `%s`', $e->getMessage()),
				$e->getCode(),
				$e,
			);
		}
	}
}
