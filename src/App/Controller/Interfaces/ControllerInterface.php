<?php

namespace NaN\App;

use Psr\Http\Message\ResponseInterface;

interface ControllerInterface {
	static public function delete(): ResponseInterface;

	static public function get(): ResponseInterface;

	static public function head(): ResponseInterface;

	static public function options(): ResponseInterface;

	static public function patch(): ResponseInterface;

	static public function post(): ResponseInterface;

	static public function put(): ResponseInterface;
}
