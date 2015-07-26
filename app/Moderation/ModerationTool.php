<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

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
	 * @var string
	 */
	protected $permissionName;

	/**
	 * @param string                $name
	 * @param string                $description
	 * @param string                $permissionName
	 * @param ModerationInterface[] $moderations
	 */
	public function __construct($name, $description, $permissionName = null, array $moderations = [])
	{
		$this->name = $name;
		$this->description = $description;
		$this->permissionName = $permissionName;

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
	 * @param array $options
	 *
	 * @return mixed
	 */
	public function apply($content, array $options = [])
	{
		foreach ($this->moderations as $moderation) {
			$moderation->apply($content);
		}
	}

	/**
	 * @param mixed $content
	 *
	 * @param array $options
	 *
	 * @return bool
	 */
	public function supports($content, array $options = [])
	{
		return true;
	}

	/**
	 * @return string
	 */
	public function getKey()
	{
		return 'custom-tool';
	}

	/**
	 * @param mixed $content
	 *
	 * @return bool
	 */
	public function visible($content)
	{
		return true;
	}

	/**
	 * @return string
	 */
	public function getPermissionName()
	{
		return $this->permissionName;
	}
}
