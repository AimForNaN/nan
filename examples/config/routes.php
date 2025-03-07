<?php

use NaN\Http\Response;

$router = new NaN\App\Middleware\Router();

$router['/'] = function () {
	return new Response(body: tpl()->render('index', [
		'title' => env('TITLE', 'NaN'),
	]));
};

return $router;