<?php declare(strict_types=1);

namespace Stefna\Http\Tests\Response;

use Stefna\Http\Response\EmptyResponse;
use Stefna\Http\Response\Status;
use PHPUnit\Framework\TestCase;

final class EmptyResponseTest extends TestCase
{
	public function testDefaultValues(): void
	{
		$response = new EmptyResponse();

		$this->assertSame(204, $response->getStatusCode());
		$this->assertSame(0, $response->getBody()->getSize());
		$this->assertSame([], $response->getHeaders());
	}

	public function testCustomCode(): void
	{
		$response = new EmptyResponse(Status::Continue);

		$this->assertSame(100, $response->getStatusCode());
		$this->assertSame(0, $response->getBody()->getSize());
	}

	public function testWithStatus(): void
	{
		$response = new EmptyResponse();
		$responseWithStatus = $response->withStatus(Status::Created->value);
		$this->assertNotSame($response, $responseWithStatus);
		$this->assertSame(Status::Created->value, $responseWithStatus->getStatusCode());
	}

	public function testWithStatusThrowExceptionForInvalidCode(): void
	{
		$response = new EmptyResponse();
		$this->expectException(\ValueError::class);
		$response->withStatus(1000);
	}
}
