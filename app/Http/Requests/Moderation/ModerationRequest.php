<?php

namespace MyBB\Core\Http\Requests\Moderation;

use MyBB\Core\Content\ContentInterface;
use MyBB\Core\Http\Requests\AbstractRequest;
use MyBB\Core\Moderation\{
    ArrayModerationInterface, DestinedInterface, ModerationInterface, ModerationRegistry, SourceableInterface
};
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
    public function rules() : array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function authorize() : bool
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
     * @return ModerationInterface|ArrayModerationInterface|DestinedInterface
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
     * @return ModerationInterface
     */
    public function getModerationByName(string $name)
    {
        return $this->moderationRegistry->get($name);
    }

    /**
     * @return array
     */
    public function getModeratableContent() : array
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
    public function getModerationOptions() : array
    {
        return $this->except([
            'moderation_content',
            'moderation_name',
            'moderation_ids',
            'moderation_source_type',
            'moderation_source_id',
            '_token',
        ]);
    }

    /**
     * @return ContentInterface
     */
    public function getDestination()
    {
        if ($this->getModeration() instanceof DestinedInterface) {
            $destinationRepository = $this->repositoryFactory->build($this->getModeration()->getDestinationType());

            return $destinationRepository->find($this->get($this->getModeration()->getDestinationKey()));
        }
    }

    /**
     * @return ContentInterface
     */
    public function getSource()
    {
        if ($this->getModeration() instanceof SourceableInterface) {
            $sourceRepository = $this->repositoryFactory->build($this->get('moderation_source_type'));
            if ($sourceRepository) {
                return $sourceRepository->find($this->get('moderation_source_id'));
            }
        }
    }
}
