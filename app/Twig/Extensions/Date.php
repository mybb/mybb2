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
	protected $_dateParser;

	/**
	 * Create a new settings extension.
	 *
	 * @param ParseDateHelper $dateParser Date Parser
	 */
	public function __construct(ParseDateHelper $dateParser)
	{
		$this->_dateParser = $dateParser;
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
			new Twig_SimpleFunction('format_date', [$this->_dateParser, 'formatDate']),
			new Twig_SimpleFunction('generate_time', [$this->_dateParser, 'generateTime']),
			new Twig_SimpleFunction('human_date', [$this->_dateParser, 'humanDate']),
		];
	}
}
