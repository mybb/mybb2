<?php

namespace MyBB\Core\Repository;

use MyBB\Core\Database\Repositories\RepositoryInterface;
use MyBB\Core\Registry\RegistryInterface;

class RepositoryRegistry implements RegistryInterface
{
	/**
	 * @var RepositoryInterface[]
	 */
	protected $repositories;

	/**
	 * @param RepositoryInterface[] $repositories
	 */
	public function __construct(array $repositories = [])
	{
		$this->repositories = $repositories;
	}

	/**
	 * @param string $key
	 * @param string $className
	 */
	public function addRepository($key, $className)
	{
		$this->repositories[$key] = $className;
	}

	/**
	 * @param string $key
	 *
	 * @return RepositoryInterface
	 */
	public function get($key)
	{
		return $this->repositories[$key];
	}

	/**
	 * @return RepositoryInterface
	 */
	public function getAll()
	{
		return $this->repositories;
	}
}
