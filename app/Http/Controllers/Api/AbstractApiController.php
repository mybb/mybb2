<?php

namespace MyBB\Core\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

abstract class AbstractApiController extends BaseController
{
	/**
	 * @var Manager
	 */
	protected $fractal;

	/**
	 * @var integer
	 */
	protected $statusCode = 200;

	/**
	 * @param Manager $fractal
	 */
	public function __construct(Manager $fractal)
	{
		$this->fractal = $fractal;
	}

	/**
	 * @return int
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * @param int $statusCode
	 *
	 * @return $this
	 */
	public function setStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;

		return $this;
	}

	/**
	 * @param mixed $item
	 * @param mixed $callback
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function respondWithItem($item, $callback)
	{
		$resource = new Item($item, $callback);
		$rootScope = $this->fractal->createData($resource);

		return $this->respondWithArray($rootScope->toArray());
	}

	/**
	 * @param mixed $collection
	 * @param mixed $callback
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function respondWithCollection($collection, $callback)
	{
		$resource = new Collection($collection, $callback);
		$rootScope = $this->fractal->createData($resource);

		return $this->respondWithArray($rootScope->toArray());
	}

	/**
	 * @param array $array
	 * @param array $headers
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function respondWithArray(array $array, array $headers = [])
	{
		return response()->json($array, $this->statusCode, $headers);
	}
}
