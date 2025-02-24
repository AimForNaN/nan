<?php

use NaN\App\App;
use NaN\App\Router\{
	Routes,
};
use NaN\DI\{
	Container,
	Definition,
	Definitions,
};

class AppBench {
	/**
	 * @Iterations(10)
	 * @Revs(10)
	 */
	public function benchRun() {
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['REQUEST_URI'] = '/';

		$app = new App(new Container(new Definitions([
			(new Definition(new Routes()))->setAlias('router')->setShared(),
		])));
		$app->run();
	}
}
