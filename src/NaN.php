<?php

use League\Container\{
	Container,
	Definition\Definition,
	Definition\DefinitionAggregate,
};
use NaN\{App,Env};

/**
 * @method static App app() Shared instance of App.
 * @method static Env env() Shared instance of Env.
 */
final class NaN {
	static private Container $registry;

	private function __construct() {
	}

	/**
	 * Conveniently get an instance of a service.
	 */
	static public function __callStatic(string $name, array $args = []) {
		return self::$registry->get($name);
	}

	static public function extend(string $name): Definition {
		return self::$registry->extend($name);
	}

	/**
	 * Called automatically! Multiple calls does nothing!
	 */
	static public function init() {
		static $init = false;
		if (!$init) {
			$init = true;
			$aggregate = new DefinitionAggregate([
				(new Definition('app', App::class))->setShared(),
				(new Definition('env', Env::class))->setShared(),
			]);
			self::$registry = new Container($aggregate);
		}
	}
}

NaN::init();
