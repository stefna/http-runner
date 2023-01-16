<?php declare(strict_types=1);

namespace Stefna\Http\ResponseHandler;

use Psr\Http\Message\ResponseInterface;

interface ResponseHandler
{
	public function getResponse(): ResponseInterface;
	public function handle(ResponseInterface $response): void;
}
