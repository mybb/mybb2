<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Twig\Extensions;

use McCool\LaravelAutoPresenter\BasePresenter;
use McCool\LaravelAutoPresenter\AutoPresenter;

class Presenter extends \Twig_Extension
{
	/**
	 * @var AutoPresenter
	 */
	protected $decorator;

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'MyBB_Twig_Extensions_Presenter';
	}

	/**
	 * @param AutoPresenter $decorator
	 */
	public function __construct(AutoPresenter $decorator)
	{
		$this->decorator = $decorator;
	}

	/**
	 * @return array
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('present', [$this, 'present']),
		];
	}

	/**
	 * @param object $object
	 *
	 * @return BasePresenter
	 */
	public function present($object)
	{
		return $this->decorator->decorate($object);
	}
}
