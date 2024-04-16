<?php

namespace NaN\App;

use NaN\Dir;

class View {
	static private array $namespaces = [];
	static public string $suffix = '.tpl.php';

	public function __construct(
		private string $tpl,
		private string $layout = null,
	) {
	}

	public function __invoke(array $data = []) {
	}

	public function include(string $tpl) {
	}

	static public function registerNamespace(string $namespace, Dir $dir) {
		static::$namespaces[$namespace] = $dir;
	}

	static public function resolve(string $path): string {
		if ($path[0] === '/') {
			if (\file_exists($path)) {
				return $path;
			} else if(\file_exists($path . static::$suffix)) {
				return $path . static::$suffix;
			}
		}

		return '';
	}
}
