<?php

namespace MyBB\Core\Permissions\Interfaces;

use Illuminate\Support\Collection;

interface PermissionInterface
{
	/**
	 * @param mixed $key
	 *
	 * @return $this
	 */
	public static function find($key);

	/**
	 * @return Collection
	 */
	public static function all();

	/**
	 * @return int
	 */
	public function getContentId();

	/**
	 * @return string
	 */
	public static function getViewablePermission();
}
