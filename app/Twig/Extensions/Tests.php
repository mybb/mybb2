<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Twig\Extensions;

use MyBB\Core\Database\Models\User;

class Tests extends \Twig_Extension
{
	/**
	 * Returns a list of tests to add to the existing list.
	 *
	 * @return array An array of tests
	 */
	public function getTests()
	{
		return [
			new \Twig_SimpleTest('user', function ($item) {
				return $item instanceof User || $item instanceof \MyBB\Core\Presenters\User;
			}),
		];
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'MyBB_Twig_Extensions_Tests';
	}
}
