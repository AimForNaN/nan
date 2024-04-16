<?php

namespace NaN\App;

use Psr\Http\Message\ServerRequestInterface;

class RoutePattern {
	private array $groups = [];
	private array $matches = [];
	private string $regex;

	public function __construct(
		private string $pattern,
	) {
	}

	public function compile(): string {
		if (!empty($this->regex)) {
			return $this->regex;
		}

		if (preg_match_all('#\{([a-zA-Z]{1}[\w]+)\}#', $this->pattern, $matches)) {
			[$matches, $groups] = $matches;
			$this->groups = $groups;
			$this->regex = $this->pattern;


			$replacements = [];
			foreach ($groups as $group) {
				$replacements['{' . $group . '}'] = '(?P<' . $group . '>[^/]+)';
			}

			$replacement = \strtr($this->regex, $replacements);
			return $this->regex = "#^{$replacement}$#i";
		}
		
		return $this->regex = "#^{$this->pattern}$#i";
	}

	public function getGroups(): array {
		return $this->groups;
	}

	public function getMatches(): array {
		return $this->matches;
	}

	public function match(ServerRequestInterface $request): bool {
		$this->matches = [];
		$path = $request->getUri()->getPath();
		$ret = \preg_match($this->regex, $path, $matches);

		foreach ($this->groups as $name) {
			$this->matches[$name] = $matches[$name];
		}

		return (bool)$ret;
	}
}
