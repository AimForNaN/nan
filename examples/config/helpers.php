<?php

use NaN\App;
use NaN\App\Middleware\Router;
use NaN\App\TemplateEngine;
use NaN\Database\Drivers\SqlDriver;
use NaN\Database\Query\Builders\SqlQueryBuilder;
use NaN\Env;

function app(): App {
	static $app = null;

	if (!$app) {
		$services = include(__DIR__ . '/services.php');
		$app = new App($services);
		$app->use(router());
	}

	return $app;
}

function db(): SqlQueryBuilder {
	static $db = null;

	if (!$db) {
		$db = new SqlDriver([
			'driver' => 'sqlite',
			'sqlite' => ':memory:',
		]);
	}

	return $db->createConnection();
}

function env(string $key, mixed $fallback = null): mixed {
	if (!Env::isLoaded()) {
		Env::load();
	}

	return Env::get($key, $fallback);
}

function dbg(mixed $msg) {
	NaN\Debug::log($msg);
}

function router(): Router {
	static $router = null;

	if (!$router) {
		$router = include(__DIR__ . '/routes.php');
	}

	return $router;
}

function tpl(): TemplateEngine {
	static $tpl = null;

	if (!$tpl) {
		$tpl = new TemplateEngine($_SERVER['DOCUMENT_ROOT'] . '/views/', 'tpl.php');
	}

	return $tpl;
}