<?php

namespace NaN\App\Controller\Traits;

use NaN\App\App;
use NaN\Http\Response;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};

trait ControllerTrait {
	public function handle(PsrServerRequestInterface $request, ?App $app = null): PsrResponseInterface {
		$method = \strtolower($request->getMethod());

		return new Response(404);
	}
}
