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
		$allowed_methods = [];

		if ($this instanceof ConnectControllerInterface) {
			$allowed_methods[] = 'CONNECT';
		}

		if ($this instanceof DeleteControllerInterface) {
			$allowed_methods[] = 'DELETE';
		}

		if ($this instanceof GetControllerInterface) {
			$allowed_methods[] = 'GET';
		}

		if ($this instanceof HeadControllerInterface) {
			$allowed_methods[] = 'HEAD';
		}

		if ($this instanceof OptionsControllerInterface) {
			$allowed_methods[] = 'OPTIONS';
		}

		if ($this instanceof PatchControllerInterface) {
			$allowed_methods[] = 'PATCH';
		}

		if ($this instanceof PostControllerInterface) {
			$allowed_methods[] = 'POST';
		}

		if ($this instanceof PutControllerInterface) {
			$allowed_methods[] = 'PUT';
		}

		if ($this instanceof TraceControllerInterface) {
			$allowed_methods[] = 'TRACE';
		}

		return $allowed_methods;
	}
}
