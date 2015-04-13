<?php

namespace MyBB\Core\Http\Requests\Moderation;

use MyBB\Core\Http\Requests\Request;
use MyBB\Core\Moderation\ModerationRegistry;
use MyBB\Core\Repository\RepositoryFactory;

class ModerationRequest extends Request
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
     * @param ModerationRegistry $moderationRegistry
     * @param RepositoryFactory $repositoryFactory
     */
    public function __construct(ModerationRegistry $moderationRegistry, RepositoryFactory $repositoryFactory)
    {
        $this->moderationRegistry = $moderationRegistry;
        $this->repositoryFactory = $repositoryFactory;
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
        return true; // TODO: check moderation permissions
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
        return $this->moderationRegistry->get($this->get('moderation_name'));
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
