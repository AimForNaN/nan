<?php

use NaN\App;
use NaN\App\Middleware;
use NaN\App\Middleware\Router;
use NaN\DI\{
	Container,
	Definition,
	Definitions,
};
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class AppBench {
	/**
	 * @Iterations(10)
	 * @Revs(10)
	 */
	public function benchNanAppStartup() {
		$app = new App();
	}

	/**
	 * @Iterations(10)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchNanAppRun() {
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['REQUEST_URI'] = '/';

		$router = new Router();
		$router['/'] = function (PsrResponseInterface $rsp) {
			return $rsp;
		};

		$app = new App(middleware: new Middleware([
			$router,
		]));
		$app->run();
	}
}
