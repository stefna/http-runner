<?php declare(strict_types=1);

namespace Stefna\Http\Util\Exception;

final class RequestAttributeNotFound extends \InvalidArgumentException
{
	public static function object(): self
	{
		return new self('Object is not of correct type');
	}
}
