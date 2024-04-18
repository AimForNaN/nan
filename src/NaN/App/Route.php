<?php

namespace NaN\App;

use Psr\Http\Message\ResponseInterface;

class Route {
	public readonly string $method;
	public readonly RoutePattern $pattern;

	/**
	 * @param string $method HTTP method.
	 * @param string $pattern URL pattern.
	 * @param mixed $handler Fully-qualified class name or callable.
	 */
	public function __construct(
		string $method,
		string $pattern,
		protected readonly mixed $handler,
	) {
		$this->method = \strtoupper($method);
		$this->pattern = new RoutePattern($pattern);
	}

	static public function all(string $pattern, mixed $handler): array {
		if (\is_a($handler, ControllerInterface::class)) {
			$handler = [$handler, 'handle'];
		}

		return [
			new static('DELETE', $pattern, $handler),
			new static('GET', $pattern, $handler),
			new static('PATCH', $pattern, $handler),
			new static('POST', $pattern, $handler),
			new static('PUT', $pattern, $handler),
		];
	}
	
	static public function controller(string $pattern, mixed $controller): array {
		if (!\is_a($controller, ControllerInterface::class)) {
			throw new \InvalidArgumentException();
		}

		if ($pattern[0] !== '/') {
		}

		return [
			new static('DELETE', $pattern, [$controller, 'remove']),
			new static('GET', $pattern, [$controller, 'index']),
			new static('PATCH', $pattern, [$controller, 'update']),
			new static('POST', $pattern, [$controller, 'create']),
			new static('PUT', $pattern, [$controller, 'replace']),
		];
	}

	static public function delete(string $pattern, mixed $handler): static {
		return new static('DELETE', $pattern, $handler);
	}

	static public function get(string $pattern, mixed $handler): static {
		return new static('GET', $pattern, $handler);
	}

	static public function patch(string $pattern, mixed $handler): static {
		return new static('PATCH', $pattern, $handler);
	}

	static public function post(string $pattern, mixed $handler): static {
		return new static('POST', $pattern, $handler);
	}

	static public function put(string $pattern, mixed $handler): static {
		return new static('PUT', $pattern, $handler);
	}

	public function toCallable(): callable {
		$handler = $this->handler;
		if (!\is_callable($handler)) {
			if (\is_array($handler)) {
				[$controller, $action] = $handler;
				if (\is_string($controller)) {
					$controller = new $controller();
					return [$controller, $action];
				}
			} else if (\class_exists($handler)) {
				if (\is_a($handler, ResponseInterface::class))  {
					return [new $handler(), 'handle'];
				}
			}
		} 
		return $handler(...);
	}
}
