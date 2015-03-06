<?php

namespace MyBB\Core\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

abstract class ApiController extends BaseController
{
	protected $fractal;

	protected $statusCode = 200;

	public function __construct(Manager $fractal)
	{
		$this->fractal = $fractal;
	}

	public function getStatusCode()
	{
		return $this->statusCode;
	}

	public function setStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;

		return $this;
	}

	protected function respondWithItem($item, $callback)
	{
		$resource = new Item($item, $callback);
		$rootScope = $this->fractal->createData($resource);

		return $this->respondWithArray($rootScope->toArray());
	}

	protected function respondWithCollection($collection, $callback)
	{
		$resource = new Collection($collection, $callback);
		$rootScope = $this->fractal->createData($resource);

		return $this->respondWithArray($rootScope->toArray());
	}

	protected function respondWithArray(array $array, array $headers = [])
	{
		return response()->json($array, $this->statusCode, $headers);
	}
}
