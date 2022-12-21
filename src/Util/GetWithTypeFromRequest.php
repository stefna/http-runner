<?php declare(strict_types=1);

namespace Moya\Http\Util;

use Moya\Http\Util\Exception\RequestAttributeNotFound;
use Psr\Http\Message\ServerRequestInterface;

trait GetWithTypeFromRequest
{
	/**
	 * @template IdClassType
	 * @param class-string<IdClassType> $idClass
	 * @return IdClassType
	 */
	public function getId(ServerRequestInterface $request, string $idClass, string $field = 'id')
	{
		$data = $request->getAttribute($field);
		if (!$data || !is_string($data)) {
			throw new \InvalidArgumentException('Invalid id specified');
		}

		return $idClass::fromString($data);
	}

	/**
	 * @template ObjectClassType
	 * @param class-string<ObjectClassType> $class
	 * @return ObjectClassType|null
	 */
	public function tryObject(ServerRequestInterface $request, string $class, ?string $key = null)
	{
		$data = $request->getAttribute($key ?? $class);
		if (!$data instanceof $class) {
			return null;
		}

		return $data;
	}

	/**
	 * @template ObjectClassType
	 * @param class-string<ObjectClassType> $class
	 * @return ObjectClassType
	 */
	public function getObject(ServerRequestInterface $request, string $class, ?string $key = null)
	{
		return $this->tryObject($request, $class, $key) ?? throw RequestAttributeNotFound::object();
	}
}
