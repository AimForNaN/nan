<?php

use NaN\App;
use NaN\App\Controller\Interfaces\{
	ControllerInterface,
	GetControllerInterface,
};
use NaN\App\Controller\Traits\ControllerTrait;
use NaN\App\Middleware\Router;
use NaN\Http\Request;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
};

describe('App', function () {
	test('Route dependency injection (closure)', function () {
		$routes = new Router();
		$routes['/'] = function (PsrResponseInterface $rsp) {
			$rsp->getBody()->write('good');
			return $rsp;
		};

		$app = new App();
		$app->use($routes);

		$rsp = $app->handle(new Request('GET', '/'));
		expect($rsp)->toBeInstanceOf(PsrResponseInterface::class);
		expect($rsp->getStatusCode())->toBe(200);
		expect((string)$rsp->getBody())->toBe('good');
	});

	test('Route param injection (closure)', function () {
		$routes = new Router();
		$routes['/{id}'] = function ($id, PsrResponseInterface $rsp) {
			expect($id)->toBe('1');
			$rsp->getBody()->write('good');
			return $rsp;
		};

		$app = new App();
		$app->use($routes);

		$rsp = $app->handle(new Request('GET', '/1'));
		expect($rsp)->toBeInstanceOf(PsrResponseInterface::class);
		expect($rsp->getStatusCode())->toBe(200);
		expect((string)$rsp->getBody())->toBe('good');
	});

	test('Route controllers', function () {
		class TestController implements ControllerInterface, GetControllerInterface {
			use ControllerTrait;

			public function get(int $id = null, ?PsrResponseInterface $rsp = null): PsrResponseInterface {
				expect($id)->toBe(1);
				expect($this)->toBeInstanceOf(TestController::class);
				expect($rsp)->toBeInstanceOf(PsrResponseInterface::class);
				$rsp->getBody()->write('good');
				return $rsp;
			}
		}

		$routes = new Router();
		$routes['/{id}'] = TestController::class;

		$app = new App();
		$app->use($routes);

		$rsp = $app->handle(new Request('GET', '/1'));
		expect($rsp->getStatusCode())->toBe(200);
		expect((string)$rsp->getBody())->toBe('good');
	});
});
