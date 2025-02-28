<?php

use NaN\App;
use NaN\App\Middleware\Router;
use NaN\DI\{
	Container,
	Definition,
	Definitions,
};

class AppBench {
	/**
	 * @Iterations(10)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchNanAppStartup() {
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['REQUEST_URI'] = '/';

		$app = new App();
		$app->run();
	}
}
