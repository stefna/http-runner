<?php declare(strict_types=1);

namespace Moya\Http\Tests\ResponseHandler;

use Moya\Http\ResponseHandler\Exception\HeadersAlreadySentException;
use Moya\Http\ResponseHandler\HttpResponseHandler;
use Moya\Http\ResponseHandler\ResponseHandler;
use Moya\Http\Tests\ResponseHandler\Mock\MockData;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

use Psr\Http\Message\StreamInterface;
use function array_filter;
use function fopen;
use function Moya\Http\ResponseHandler\header;
use function Moya\Http\ResponseHandler\headers_list;
use function Moya\Http\ResponseHandler\http_response_code;
use function Moya\Http\ResponseHandler\http_response_status_line;
use function implode;
use function is_int;
use function preg_replace;
use function strlen;

final class HttpResponseHandlerTest extends TestCase
{
	protected function setUp(): void
	{
		require 'Mock/FunctionMocks.php';
		MockData::reset();
	}

	public function testDefault(): void
	{
		$response = $this->createResponse();
		(new HttpResponseHandler())->handle($response);

		$this->assertSame(200, http_response_code());
		$this->assertCount(0, headers_list());
		$this->assertSame([], headers_list());
		$this->assertSame('HTTP/1.1 200 OK', http_response_status_line());
		$this->expectOutputString('');
	}

	public function testStreamPassThru(): void
	{
		/** @var resource $resource */
		$resource = fopen('php://temp', 'rw+');

		$response = $this->createResponse(
			$code = 200,
			[
				HttpResponseHandler::PASSTHRU => '1',
			],
			Stream::create($resource),
		);
		(new HttpResponseHandler())->handle($response);

		$this->assertSame($code, http_response_code());
		$this->assertCount(0, headers_list());
		$this->assertSame($resource, MockData::$passThruResource);
	}

	public function testWithSpecifyArguments(): void
	{
		$response = $this->createResponse($code = 404, ['X-Test' => 'test'], $contents = 'Page not found', '2');
		(new HttpResponseHandler())->handle($response);

		$this->assertSame($code, http_response_code());
		$this->assertCount(1, headers_list());
		$this->assertSame(['X-Test: test'], headers_list());
		$this->assertSame('HTTP/2 404 Not Found', http_response_status_line());
		$this->expectOutputString($contents);
	}

	public function testDuplicateHeadersNotReplaced(): void
	{
		$response = $this->createResponse($code = 200, ['X-Test' => 'test-1'], $contents = 'Contents')
			->withAddedHeader('X-Test', 'test-2')
			->withAddedHeader('X-Test', 'test-3')
			->withAddedHeader('Set-Cookie', 'key-1=value-1')
			->withAddedHeader('Set-Cookie', 'key-2=value-2')
		;

		(new HttpResponseHandler())->handle($response);

		$expectedHeaders = [
			'X-Test: test-1',
			'X-Test: test-2',
			'X-Test: test-3',
			'Set-Cookie: key-1=value-1',
			'Set-Cookie: key-2=value-2',
		];

		$this->assertSame($code, http_response_code());
		$this->assertSame($expectedHeaders, headers_list());
		$this->assertSame('HTTP/1.1 200 OK', http_response_status_line());
		$this->expectOutputString($contents);
	}

	public function testBodyWithNotReadableStream(): void
	{
		// @phpstan-ignore-next-line - no it's never going to be false
		$response = new Response(200, [], Stream::create(fopen('php://output', 'c')));
		$this->assertSame('php://output', $response->getBody()->getMetadata('uri'));
		$this->assertFalse($response->getBody()->isReadable());

		$responseHandler = new HttpResponseHandler();
		$responseHandler->handle($response);
		$this->expectOutputString('');
	}

	public function testThrowHeadersAlreadySentException(): void
	{
		MockData::$isHeadersSent = true;

		$this->expectException(HeadersAlreadySentException::class);
		$this->expectExceptionMessage('Unable to send response. Headers already sent');

		(new HttpResponseHandler())->handle($this->createResponse());
	}

	/**
	 * @param array<string, string> $headers
	 */
	private function createResponse(
		int $statusCode = 200,
		array $headers = [],
		string|StreamInterface $contents = '',
		string $protocol = '1.1'
	): ResponseInterface {
		$response = new Response(
			$statusCode,
			$headers,
			Stream::create(''),
			$protocol
		);
		if ($contents instanceof StreamInterface) {
			return $response->withBody($contents);
		}
		$response->getBody()->write($contents);
		return $response;
	}
}
