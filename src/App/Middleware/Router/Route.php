<?php

namespace NaN\App\Middleware\Router;

use NaN\App\Controller\Interfaces\ControllerInterface;
use NaN\Http\Response;
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

class Route implements \ArrayAccess {
	protected array $children = [];

	public function __construct(
		public ?string $path = null,
		public mixed $handler = null,
	) {
	}

	static public function fromArray(array $routes): self {
		$path = $routes[':path'] ?? null;
		$handler = $routes[':handler'] ?? null;
		$route = new self($path, $handler);
		unset($routes[':path']);
		unset($routes[':handler']);

		foreach ($routes as $part => $route_struct) {
			$route->insert($part, self::fromArray($route_struct));
		}

		return $route;
	}

	public function insert(string $part, Route $route): self {
		$this->children[$part] = $route;
		return $this;
	}

	public function isValid(): bool {
		return \is_callable($this->handler) || \is_a($this->handler, ControllerInterface::class);
	}

	public function match(string $part): ?Route {
		if (isset($this->children[$part])) {
			return $this->children[$part];
		}

		foreach ($this->children as $path => $child) {
			$pattern = new RoutePattern($path);
			$pattern->compile();

			if ($pattern->matches($part)) {
				return $child;
			}
		}

		return null;
	}

	public function matches(string $path): bool {
		$pattern = new RoutePattern($this->path);
		$pattern->compile();
		return $pattern->matches($path);
	}

	public function matchesRequest(PsrServerRequestInterface $request): bool {
		return $this->matches($request->getUri()->getPath());
	}

	public function offsetExists(mixed $offset): bool {
		return isset($this->children[$offset]);
	}

	public function offsetGet(mixed $offset): mixed {
		return $this->children[$offset] ?? null;
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		$this->insert($offset, $value);
	}

	public function offsetUnset(mixed $offset): void {
		unset($this->children[$offset]);
	}

	public function toCallable(PsrServerRequestInterface $request): callable {
		$handler = $this->handler;
		if (!\is_callable($handler)) {
			if (\is_subclass_of($handler, ControllerInterface::class))  {
				$handler = new $handler();
				$allowed_methods = $handler->getAllowedMethods();
				$method = $request->getMethod();

				if ($allowed_methods[$method] ?? false) {
					return \Closure::fromCallable([$handler, \strtolower($method)]);
				}
			}

			$handler = function () {
				return new Response(501);
			};
		}
		return $handler(...);
	}

	public function withHandler(mixed $handler): static {
		$route = clone $this;
		$route->handler = $handler;
		return $route;
	}

	public function withPath(string $path): static {
		$route = clone $this;
		$route->path = $path;
		return $route;
	}
}
