<?php declare(strict_types=1);

namespace Moya\Http\Tests\ResponseHandler;

use Moya\Http\ResponseHandler\CliResponseHandler;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

class CliResponseHandlerTest extends TestCase
{
	public function testDefaultResponse(): void
	{
		$responseHandler = new CliResponseHandler();

		$response = new Response(
			200,
			[
				'X-Test' => '1',
			],
			'test-body',
		);

		ob_start();
		$responseHandler->handle($response);
		$content = ob_get_clean();

		$lines = implode(PHP_EOL, [
			'Headers: ',
			'	X-Test: 1',
			'',
			'Response status: 200',
			'test-body',
		]);

		$this->assertSame($lines, $content);
	}
}
