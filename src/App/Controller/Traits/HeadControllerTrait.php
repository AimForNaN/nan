<?php

namespace NaN\App\Controller\Traits;

use NaN\Http\Response;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

trait HeadControllerTrait {
	public function head(): PsrResponseInterface {
		return new Response(501);
	}
}