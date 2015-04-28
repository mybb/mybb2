<?php

namespace MyBB\Core\Presenters\Moderations;

use MyBB\Core\Form\RenderableInterface;

interface ModerationPresenterInterface
{
	/**
	 * @return RenderableInterface[]
	 */
	public function fields();

	/**
	 * @return string
	 */
	public function icon();
}