<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Form;

interface RenderableInterface
{
	/**
	 * @return string
	 */
	public function getType();

	/**
	 * @return array
	 */
	public function getOptions();

	/**
	 * @return string
	 */
	public function getDescription();

	/**
	 * @return string
	 */
	public function getElementName();

	/**
	 * @return string
	 */
	public function getLabel();

	/**
	 * @return mixed
	 */
	public function getValue();

	/**
	 * @return array
	 */
	public function getValidationRules();
}
