<?php

namespace NaN\App\Controller\Traits;

use NaN\App\App;
use NaN\DI\{
	Container,
	Definition,
	Definitions,
};
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
use NaN\Http\Request;
use NaN\Http\Response;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
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

	public function handle(PsrServerRequestInterface $request, ?App $app = null): PsrResponseInterface {
		$allowed_methods = $this->getAllowedMethods();
		$method = $request->getMethod();

		if (!\in_array($method, $allowed_methods, true)) {
			return new Response(405);
		}

		$definitions = new Definitions([
			(new Definition($request))->setAlias(Request::class)->setShared(),
		]);

		if ($app) {
			$definitions[] = (new Definition($app))->setAlias(App::class)->setShared();
		}

		$container = new Container($definitions);
		$definition = (new Definition($this))->addMethodCall(\strtolower($method));

		return $definition->resolve($container);
	}
}
