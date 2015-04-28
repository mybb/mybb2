<?php

namespace MyBB\Core\Moderation;

class ModerationTool implements ModerationInterface
{
	/**
	 * @var ModerationInterface[]
	 */
	protected $moderations;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @param string                $name
	 * @param string                $description
	 * @param ModerationInterface[] $moderations
	 */
	public function __construct($name, $description, array $moderations = [])
	{
		$this->name = $name;
		$this->description = $description;

		foreach ($moderations as $moderation) {
			$this->addModeration($moderation);
		}
	}

	/**
	 * @param ModerationInterface $moderation
	 */
	public function addModeration(ModerationInterface $moderation)
	{
		$this->moderations[$moderation->getName()] = $moderation;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param mixed $content
	 *
	 * @return mixed
	 */
	public function apply($content)
	{
		foreach ($this->moderations as $moderation) {
			$moderation->apply($content);
		}
	}

	/**
	 * @param mixed $content
	 *
	 * @return bool
	 */
	public function supports($content)
	{
		return true;
	}
}
