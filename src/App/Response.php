<?php

namespace NaN\App;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;

class Response extends \GuzzleHttp\Psr7\Response {
	static public function redirect(string $path, int $status = 302): ResponseInterface {
		return new static($status, [
			'Location' => $path,
		]);
	}

	static public function send(ResponseInterface $rsp) {
		$status = $rsp->getStatusCode();
		\http_response_code($status);

		static::sendHeaders($rsp);

		if ($rsp->getStatusCode() !== 204) {
			static::sendBody($rsp);
		}
	}

	static public function sendBody(ResponseInterface $rsp) {
		$out = Utils::streamFor(fopen('php://output', 'w'));
		$content = (string)$rsp->getBody();
		$out->write($content);
	}

	static public function sendHeaders(ResponseInterface $rsp) {
		$headers = $rsp->getHeaders();
		foreach ($headers as $name => $value) {
			$value = \implode(';', $value);
			\header("{$name}: {$value}", true);
		}
	}
}
