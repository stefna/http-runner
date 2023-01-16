<?php declare(strict_types=1);

namespace Stefna\Http\Tests\Response;

use Stefna\Http\Response\JsonResponse;
use Stefna\Http\Response\Status;
use PHPUnit\Framework\TestCase;

final class JsonResponseTest extends TestCase
{
	/** @var array<mixed> */
	private array $data = ['key' => 'value'];
	private string $contentType = 'application/json; charset=UTF-8';
	private JsonResponse $response;

	public function setUp(): void
	{
		$this->response = new JsonResponse($this->data);
	}

	public function testGettersDefault(): void
	{
		$this->assertSame(Status::Ok->value, $this->response->getStatusCode());
		$this->assertSame('php://temp', $this->response->getBody()->getMetadata('uri'));
		$this->assertSame(
			json_encode($this->data, JsonResponse::DEFAULT_OPTIONS),
			$this->response->getBody()->__toString()
		);
		$this->assertSame($this->contentType, $this->response->getHeaderLine('content-type'));
	}

	public function testOverrideContentType(): void
	{
		$response = new JsonResponse(
			$this->data,
			$status = Status::NotFound,
		);
		$response = $response->withHeader('Content-Type', 'text/plain; charset=UTF-8');
		$this->assertSame(
			json_encode($this->data, JsonResponse::DEFAULT_OPTIONS),
			$this->response->getBody()->__toString()
		);
		$this->assertSame($status->value, $response->getStatusCode());
		$this->assertSame(
			[
				'Content-Type' => ['text/plain; charset=UTF-8'],
			],
			$response->getHeaders()
		);
	}

	public function testGettersSpecifiedArgumentsWithCustomEncodingOptions(): void
	{
		$response = new JsonResponse(
			$data = ['text' => "O'Reilly"],
			$status = Status::NotFound,
			$encodingOptions = JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
		);
		$this->assertSame(json_encode($data, $encodingOptions), $response->getBody()->__toString());
		$this->assertSame($status->value, $response->getStatusCode());
		$this->assertSame(
			[
				'Content-Type' => [$this->contentType],
			],
			$response->getHeaders()
		);
	}

	public function testWithJsonDataWithCustomEncodingOptions(): void
	{
		$response = $this->response->withJsonData(
			$data = ['text' => "O'Reilly"],
			$encodingOptions = JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
		);
		$this->assertNotSame($response, $this->response);
		$this->assertSame(json_encode($data, $encodingOptions), $response->getBody()->__toString());
		$this->assertSame(Status::Ok->value, $response->getStatusCode());
		$this->assertSame(['Content-Type' => [$this->contentType]], $response->getHeaders());
	}

	public function testWithJsonDataHasBeenClonedForSpecifiedObjectData(): void
	{
		$data = new \stdClass();
		$response = $this->response->withJsonData($data);
		$this->assertNotSame($response, $this->response);
		$response2 = $response->withJsonData($data);
		$this->assertNotSame($response2, $response);
		$response3 = $response2->withJsonData($data);
		$this->assertNotSame($response3, $response2);
		$this->assertNotSame($response3, $response);
	}

	public function testFailOnResource(): void
	{
		$this->expectExceptionMessage('Resources cannot be encoded in JSON');
		$this->expectException(\InvalidArgumentException::class);
		new JsonResponse(fopen('php://temp', 'r+'));
	}

	public function testJsonThrowOnInvalidData(): void
	{
		try {
			new JsonResponse("\xB1\x31");
			$this->fail('Should crash on invalid input');
		}
		catch (\InvalidArgumentException $e) {
			$this->assertInstanceOf(\JsonException::class, $e->getPrevious());
		}
	}
}
