<?php

namespace NaN\Http;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;

class Response extends \GuzzleHttp\Psr7\Response {
	public function __construct(int $status = 200, array $headers = [], $body = null, ?string $version = null, ?string $reason = null) {
		if (!$version) {
			[, $version] = \explode('/', $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1');
		}
		parent::__construct($status, $headers, $body, $version, $reason);
	}

	static public function json(mixed $data) {
		return new static(200, [
			'Content-Type' => 'application/json',
		], \json_encode($data));
	}

	static public function redirect(string $path, int $status = 302): ResponseInterface {
		return new static($status, [
			'Location' => $path,
		]);
	}

	static public function send(ResponseInterface $rsp) {
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
		$protocol = $rsp->getProtocolVersion();
		$status = $rsp->getStatusCode();
		$phrase = $rsp->getReasonPhrase();
		\header("HTTP/{$protocol} {$status} {$phrase}");

		$headers = $rsp->getHeaders();
		foreach ($headers as $name => $value) {
			$value = \implode(';', $value);
			\header("{$name}: {$value}");
		}
	}
}
