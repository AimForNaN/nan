<?php

namespace NaN\App\Controller\Traits;

use NaN\App\Controller\Interfaces\{
	ConnectControllerInterface,
	DeleteControllerInterface,
	GetControllerInterface,
	HeadControllerInterface,
	OptionsControllerInterface,
	PatchControllerInterface,
	PostControllerInterface,
	PutControllerInterface,
	TraceControllerInterface,
};

trait ControllerTrait {
	public function getAllowedMethods(): array {
		return [
			'CONNECT' => $this instanceof ConnectControllerInterface,
			'DELETE' => $this instanceof DeleteControllerInterface,
			'GET' => $this instanceof GetControllerInterface,
			'HEAD' => $this instanceof HeadControllerInterface,
			'OPTIONS' => $this instanceof OptionsControllerInterface,
			'PATCH' => $this instanceof PatchControllerInterface,
			'POST' => $this instanceof PostControllerInterface,
			'PUT' => $this instanceof PutControllerInterface,
			'TRACE' => $this instanceof TraceControllerInterface,
		];
	}
}
