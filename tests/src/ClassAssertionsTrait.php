<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Test;

trait ClassAssertionsTrait
{
	/**
	 * @param mixed $class
	 * @param string $extends
	 */
	public static function assertClassExtends($class, $extends)
	{
		static::assertContains($extends, class_parents($class));
	}
}
