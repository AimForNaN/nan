<?php

namespace NaN\App\Router;

use NaN\App\Controller\Interfaces\ControllerInterface;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};

class Route implements \ArrayAccess {
	protected array $children = [];

	public function __construct(
		protected ?string $path,
		protected mixed $handler = null,
	) {
	}

	public function getPath(): ?string {
		return $this->path;
	}

	public function isValid(): bool {
		return \is_callable($this->handler) || \is_a($this->handler, ControllerInterface::class);
	}

	public function matches(PsrServerRequestInterface $request): bool {
		$pattern = new RoutePattern($this->path);
		$pattern->compile();
		return $pattern->matchesRequest($request);
	}

	public function offsetExists(mixed $offset): bool {
		return (bool)$this->offsetGet($offset);
	}

	public function offsetGet(mixed $offset): mixed {
		if (isset($this->children[$offset])) {
			return $this->children[$offset];
		}

		foreach ($this->children as $path => $child) {
			$pattern = new RoutePattern($path);
			$pattern->compile();

			if ($pattern->matches($offset)) {
				return $child;
			}
		}

		return null;
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		$this->children[$offset] = $value;
	}

	public function offsetUnset(mixed $offset): void {
		unset($this->children[$offset]);
	}

	public function setHandler(mixed $handler): void {
		$this->handler = $handler;
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
			} else if (\is_a($handler, PsrResponseInterface::class))  {
				return [new $handler(), 'handle'];
			}
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
