<?php
/**
 * Date extension for Twig.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Twig\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;

class Date extends Twig_Extension
{
	/**
	 * @var ParseDateHelper $_dateParser ;
	 */
	protected $dateParser;

	/**
	 * Create a new settings extension.
	 *
	 * @param ParseDateHelper $dateParser Date Parser
	 */
	public function __construct(ParseDateHelper $dateParser)
	{
		$this->dateParser = $dateParser;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'MyBB_Twig_Extensions_Date';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions()
	{
		return [
			new Twig_SimpleFunction('format_date', [$this->dateParser, 'formatDate']),
			new Twig_SimpleFunction('generate_time', [$this->dateParser, 'generateTime'], ['is_safe' => ['html']]),
			new Twig_SimpleFunction('human_date', [$this->dateParser, 'humanDate']),
			new Twig_SimpleFunction('post_date_link', [$this->dateParser, 'postDateLink'], ['is_safe' => ['html']]),
		];
	}
}
