<?php declare(strict_types=1);

namespace Stefna\Http\Tests\Response;

use Stefna\Http\Response\RedirectResponse;
use Stefna\Http\Response\Status;
use PHPUnit\Framework\TestCase;

final class RedirectResponseTest extends TestCase
{
	private string $uri = 'https://example.com/path?query=string#fragment';
	private RedirectResponse $response;

	public function setUp(): void
	{
		$this->response = new RedirectResponse($this->uri);
	}

	public function testGettersDefault(): void
	{
		$this->assertSame(Status::Found->value, $this->response->getStatusCode());
		$this->assertSame('php://temp', $this->response->getBody()->getMetadata('uri'));
		$this->assertSame($this->uri, $this->response->getHeaderLine('location'));
	}

	public function testGettersWithStatus301MovedPermanently(): void
	{

		$response = new RedirectResponse(
			$uri = 'https://example.com/path?query=string#fragment',
			$status = Status::MovedPermanently
		);
		$this->assertSame($status->value, $response->getStatusCode());
		$this->assertSame('Moved Permanently', $response->getReasonPhrase());
		$this->assertSame('php://temp', $response->getBody()->getMetadata('uri'));
		$this->assertSame($uri, $response->getHeaderLine('location'));
	}

	public function testWithStatus(): void
	{
		$response = $this->response->withStatus(Status::MovedPermanently->value );
		$this->assertNotSame($this->response, $response);
		$this->assertSame(Status::MovedPermanently->value, $response->getStatusCode());
		$this->assertSame('Moved Permanently', $response->getReasonPhrase());
	}
}
