<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Twig\Extensions;

use MyBB\Core\Form\Renderer;

class Form extends \Twig_Extension
{
	/**
	 * @var Renderer
	 */
	protected $renderer;

	/**
	 * @param Renderer $renderer
	 */
	public function __construct(Renderer $renderer)
	{
		$this->renderer = $renderer;
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'MyBB_Twig_Extensions_Form';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('form_render_field', [$this->renderer, 'render'], ['is_safe' => ['html']]),
		];
	}
}
