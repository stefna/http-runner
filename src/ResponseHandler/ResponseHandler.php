<?php declare(strict_types=1);

namespace Moya\Http\ResponseHandler;

use Psr\Http\Message\ResponseInterface;

interface ResponseHandler
{
	public function getResponse(): ResponseInterface;
	public function handle(ResponseInterface $response): void;
}
