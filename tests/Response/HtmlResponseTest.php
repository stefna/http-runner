<?php declare(strict_types=1);

namespace Stefna\Http\Tests\Response;

use Stefna\Http\Response\HtmlResponse;
use Stefna\Http\Response\Status;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

final class HtmlResponseTest extends TestCase
{
	private string $content = '<p>HTML</p>';
	private string $contentType = 'text/html; charset=UTF-8';

	public function testDefaultStatus(): void
	{
		$response = new HtmlResponse($this->content);
		$this->assertSame(Status::Ok->value, $response->getStatusCode());
		$this->assertSame('OK', $response->getReasonPhrase());
		$this->assertSame('php://temp', $response->getBody()->getMetadata('uri'));
		$this->assertSame($this->content, $response->getBody()->__toString());
		$this->assertSame($this->contentType, $response->getHeaderLine('content-type'));
	}

	public function testAddingHeaders(): void
	{
		$response = new HtmlResponse(
			$this->content,
			$status = Status::NotFound,
		);
		$response = $response->withHeader('Content-Language', 'en');
		$this->assertSame($this->content, $response->getBody()->__toString());
		$this->assertSame($status->value, $response->getStatusCode());
		$this->assertSame(
			[
				'Content-Type' => [$this->contentType],
				'Content-Language' => ['en'],
			],
			$response->getHeaders()
		);
	}

	public function testOverrideContentType(): void
	{
		$response = new HtmlResponse(
			$this->content,
			$status = Status::NotFound,
		);
		$response = $response->withHeader('Content-Type', 'text/plain; charset=UTF-8');
		$this->assertSame($this->content, $response->getBody()->__toString());
		$this->assertSame($status->value, $response->getStatusCode());
		$this->assertSame(
			[
				'Content-Type' => ['text/plain; charset=UTF-8'],
			],
			$response->getHeaders()
		);
	}
}
