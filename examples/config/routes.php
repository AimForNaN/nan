<?php

use NaN\App\Middleware\Router;
use NaN\App\TemplateEngine;
use NaN\Env;
use NaN\Http\Response;

$router = new Router();

$router['/'] = function (TemplateEngine $tpl) {
	return new Response(body: $tpl->render('index', [
		'title' => Env::get('TITLE', 'NaN'),
	]));
};

return $router;