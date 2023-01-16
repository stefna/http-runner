<?php declare(strict_types=1);

namespace Stefna\Http\Tests\Response;

use Stefna\Http\Response\Status;
use Stefna\Http\Response\TextResponse;
use PHPUnit\Framework\TestCase;

final class TextResponseTest extends TestCase
{
	private string $content = 'Text';
	private string $contentType = 'text/plain; charset=UTF-8';
	private TextResponse $response;

	public function setUp(): void
	{
		$this->response = new TextResponse($this->content);
	}

	public function testGettersDefault(): void
	{
		$this->assertSame(Status::Ok->value, $this->response->getStatusCode());
		$this->assertSame('OK', $this->response->getReasonPhrase());
		$this->assertSame('php://temp', $this->response->getBody()->getMetadata('uri'));
		$this->assertSame($this->content, $this->response->getBody()->__toString());
		$this->assertSame($this->contentType, $this->response->getHeaderLine('content-type'));
	}

	public function testGettersIfHasBeenPassedContentTypeHeader(): void
	{
		$response = new TextResponse(
			$this->content,
			$status = Status::NotFound,
		);
		$response = $response
			->withHeader('Content-Language', 'en')
			->withHeader('Content-Type', 'text/csv');
		$this->assertSame($this->content, $response->getBody()->__toString());
		$this->assertSame($status->value, $response->getStatusCode());
		$this->assertSame(
			[
				'Content-Language' => ['en'],
				'Content-Type' => ['text/csv'],
			],
			$response->getHeaders()
		);
	}

	public function testWithStatus(): void
	{
		$response = $this->response->withStatus(Status::NotFound->value);
		$this->assertNotSame($this->response, $response);
		$this->assertSame(Status::NotFound->value, $response->getStatusCode());
		$this->assertSame('Not Found', $response->getReasonPhrase());
	}
}
