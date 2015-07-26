<?php

namespace MyBB\Core\Twig\Extensions;

use McCool\LaravelAutoPresenter\BasePresenter;
use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Core\Moderation\ArrayModerationInterface;
use MyBB\Core\Moderation\ReversibleModerationInterface;
use MyBB\Core\Presenters\Moderations\ReversibleModerationPresenterInterface;

class Moderation extends \Twig_Extension
{
	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'MyBB_Twig_Extensions_Moderation';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('is_reversible_moderation', [$this, 'isReversibleModeration']),
			new \Twig_SimpleFunction('is_array_moderation', [$this, 'isArrayModeration']),
		];
	}

	/**
	 * @param object $moderation
	 *
	 * @return bool
	 */
	public function isReversibleModeration($moderation)
	{
		return $moderation instanceof ReversibleModerationInterface
			|| $moderation instanceof ReversibleModerationPresenterInterface;
	}

	/**
	 * @param object $moderation
	 *
	 * @return bool
	 */
	public function isArrayModeration($moderation)
	{
		if ($moderation instanceof BasePresenter) {
			return $moderation->getWrappedObject() instanceof ArrayModerationInterface;
		}
		return $moderation instanceof ArrayModerationInterface;
	}
}
