<?php

namespace NaN\App\Controller\Traits;

use NaN\Http\Response;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

trait OptionsControllerTrait {
	public function options(): PsrResponseInterface {
		return new Response(404);
	}
}