<?php

namespace MyBB\Core\Http\Requests\Moderation;

use MyBB\Core\Http\Requests\AbstractRequest;
use MyBB\Core\Moderation\ModerationRegistry;
use MyBB\Core\Permissions\PermissionChecker;
use MyBB\Core\Repository\RepositoryFactory;

class ModerationRequest extends AbstractRequest
{
	/**
	 * @var ModerationRegistry
	 */
	protected $moderationRegistry;

	/**
	 * @var RepositoryFactory
	 */
	protected $repositoryFactory;

	/**
	 * @var PermissionChecker
	 */
	protected $permissionChecker;

	/**
	 * @param ModerationRegistry $moderationRegistry
	 * @param RepositoryFactory $repositoryFactory
	 * @param PermissionChecker $permissionChecker
	 */
	public function __construct(
		ModerationRegistry $moderationRegistry,
		RepositoryFactory $repositoryFactory,
		PermissionChecker $permissionChecker
	) {
		$this->moderationRegistry = $moderationRegistry;
		$this->repositoryFactory = $repositoryFactory;
		$this->permissionChecker = $permissionChecker;
	}

	/**
	 * @return array
	 */
	public function rules()
	{
		return [];
	}

	/**
	 * @return bool
	 */
	public function authorize()
	{
		if ($this->getModeration()) {
			return $this->permissionChecker->hasPermission('user', null, $this->getModeration()->getPermissionName());
		}

		return true;
	}

	/**
	 * @return \MyBB\Core\Database\Repositories\RepositoryInterface
	 */
	public function getRepository()
	{
		return $this->repositoryFactory->build($this->get('moderation_content'));
	}

	/**
	 * @return \MyBB\Core\Moderation\ModerationInterface|\MyBB\Core\Moderation\ArrayModerationInterface
	 */
	public function getModeration()
	{
		if ($this->get('moderation_name')) {
			return $this->moderationRegistry->get($this->get('moderation_name'));
		}
	}

	/**
	 * @param string $name
	 *
	 * @return \MyBB\Core\Moderation\ModerationInterface
	 */
	public function getModerationByName($name)
	{
		return $this->moderationRegistry->get($name);
	}

	/**
	 * @return array
	 */
	public function getModeratableContent()
	{
		$content = [];

		foreach ($this->get('moderation_ids') as $id) {
			$content[] = $this->getRepository()->find($id);
		}

		return $content;
	}

	/**
	 * @return array
	 */
	public function getModerationOptions()
	{
		return $this->except(['moderation_content', 'moderation_name', 'moderation_ids', '_token']);
	}
}
