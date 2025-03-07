<?php

use NaN\App;

NaN\Env::load();

function router() {
	static $router = null;

	if (!$router) {
		$router = include(__DIR__ . '/routes.php');
	}

	return $router;
}

function app() {
	static $app = null;

	if (!$app) {
		$services = include(__DIR__ . '/services.php');
		$app = new App($services);
		$app->use(router());
	}

	return $app;
}

function env(string $key, mixed $fallback = null) {
	return NaN\Env::get($key, $fallback);
}