<?php

namespace MyBB\Core\Presenters\Moderations;

use MyBB\Core\Content\ContentInterface;
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

	/**
	 * @return string
	 */
	public function key();

	/**
	 * @return string
	 */
	public function name();

	/**
	 * @param array            $contentCollection
	 * @param ContentInterface $source
	 * @param ContentInterface $destination
	 *
	 * @return string
	 */
	public function describe(array $contentCollection, ContentInterface $source = null, ContentInterface $destination = null);
}
