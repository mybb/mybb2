<?php
/**
 * Captcha extension for Twig.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Twig\Extensions;

use MyBB\Core\Captcha\CaptchaFactory;
use Twig_Extension;
use Twig_SimpleFunction;

class Captcha extends Twig_Extension
{

	protected $_captcha;


	public function __construct(CaptchaFactory $captcha)
	{
		$this->_captcha = $captcha;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'MyBB_Twig_Extensions_Captcha';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions()
	{
		return [
			new Twig_SimpleFunction('captcha', [$this->_captcha, 'render'], ['is_safe' => ['html']]),
		];
	}
}
