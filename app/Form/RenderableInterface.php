<?php

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
	public function getName();

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
