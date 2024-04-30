<?php

namespace NaN\App;

use NaN\DI\{
	Arguments\Arguments,
	Definition,
};
use Psr\Container\ContainerInterface as PsrContainerInterface;
use Psr\Http\{
	Message\ResponseInterface as PsrResponseInterface,
	Message\ServerRequestInterface as PsrServerRequestInterface,
	Server\RequestHandlerInterface as PsrRequestHandlerInterface,
};

class Route implements PsrRequestHandlerInterface {
	public readonly string $method;
	protected RoutePattern $pattern;

	/**
	 * @param string $method HTTP method.
	 * @param string $pattern URL pattern.
	 * @param mixed $handler Fully-qualified class name or callable.
	 */
	public function __construct(
		string $method,
		string $pattern,
		private mixed $handler,
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

	public function handle(PsrServerRequestInterface $request, PsrContainerInterface $container = null): PsrResponseInterface {
		$this->pattern->compile();
		$this->pattern->match($request);

		$values = $this->pattern->getMatches();
		$handler = $this->toCallable();
		$arguments = Arguments::fromCallable($handler, $values);
		$definition = new Definition($handler, $arguments->toArray());

		return $definition->resolve($container);
	}

	public function matches(PsrServerRequestInterface $request) {
		$this->pattern->compile();
		return $this->pattern->match($request);
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
				if (\is_a($handler, PsrResponseInterface::class))  {
					return [new $handler(), 'handle'];
				}
			}
		} 
		return $handler(...);
	}
}
