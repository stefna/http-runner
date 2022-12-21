<?php declare(strict_types=1);

namespace Moya\Http\Tests\ResponseHandler\Mock;

final class MockData
{
	/** @var array<string, string[]> */
	public static array $headers = [];

	public static int $statusCode = 200;

	public static string $statusLine = '';

	public static bool $isHeadersSent = false;

	/** @var string[] */
	public static array $contentSplitByBytes = [];

	/** @var resource|null */
	public static $passThruResource = null;

	/**
	 * Reset data.
	 */
	public static function reset(): void
	{
		self::$headers = [];
		self::$statusCode = 200;
		self::$statusLine = '';
		self::$isHeadersSent = false;
		self::$contentSplitByBytes = [];
		self::$passThruResource = null;
	}
}
