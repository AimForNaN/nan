<?php

namespace NaN\App;

use Psr\Http\Message\ResponseInterface;

class Route {
	public readonly RoutePattern $pattern;

	/**
	 * @param string $method HTTP method.
	 * @param string $pattern URL pattern.
	 * @param mixed $handler Fully-qualified class name or callable.
	 */
	public function __construct(
		public readonly string $method,
		string $pattern,
		protected readonly mixed $handler,
	) {
		$this->pattern = new RoutePattern($pattern);
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

	static public function singleton(string $pattern, mixed $controller): array {
		return [
			new static('DELETE', $pattern, [$controller, 'handle']),
			new static('GET', $pattern, [$controller, 'handle']),
			new static('PATCH', $pattern, [$controller, 'handle']),
			new static('POST', $pattern, [$controller, 'handle']),
			new static('PUT', $pattern, [$controller, 'handle']),
		];
	}

	public function toCallable(): callable {
		$handler = $this->handler;
		if (!\is_callable($handler)) {
			if (\class_exists($handler)) {
				if (\is_a($handler, ResponseInterface::class))  {
					return [new $handler(), 'handle'];
				}
			} else {
				[$controller, $action] = $handler;
				if (\is_string($controller)) {
					$controller = new $controller();
					return [$controller, $action];
				}
			}
		} 
		return $handler;
	}
}
