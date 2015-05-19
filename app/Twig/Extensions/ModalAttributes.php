<?php
/**
 * Extension for Twig to render a user profile link for a given user.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Twig\Extensions;

use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Presenters\User;
use Twig_SimpleFunction;

class ModalAttributes extends \Twig_Extension
{

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'MyBB_Twig_Extensions_ModalAttributes';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions()
	{
		return [
			new Twig_SimpleFunction('modal_attributes', [$this, 'renderModalAttributes'], ['is_safe' => ['html']]),
		];
	}

	/**
	 * @param string $name       Either the name of a route or a direct path
	 * @param array  $parameters The parameters passed to the route. Not supported for paths
	 * @param bool   $isRoute    Indicates whether $name is a route or a path
	 *
	 * @return string
	 */
	public function renderModalAttributes($name, $parameters = array(), $isRoute = true)
	{
		if ($isRoute) {
			$href = route($name, $parameters);
			$modal = ltrim(route($name, $parameters, false), '/');
		} else {
			$href = url($name);
			$modal = ltrim($name, '/');
		}

		return "href=\"{$href}\" data-modal=\"{$modal}\"";
	}
}
