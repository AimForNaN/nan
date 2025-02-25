<?php

namespace NaN\App\Controller\Traits;

use NaN\Http\Response;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

trait TraceControllerTrait {
	public function trace(): PsrResponseInterface {
		return new Response(501);
	}
}