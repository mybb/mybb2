<?php

namespace MyBB\Core\Http\Controllers;

use Illuminate\Http\Request;
use MyBB\Core\Moderation\ModerationRegistry;
use MyBB\Core\Moderation\ReversableModerationInterface;
use MyBB\Core\Repository\RepositoryFactory;

class ModerationController extends Controller
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
     * @param Request $request
     */
    public function moderate(Request $request)
    {
        $moderationContent = $request->get('moderation_content');
        $moderationIds = $request->get('moderation_ids');
        $moderationName = $request->get('moderation_name');

        $moderation = $this->moderationRegistry->get($moderationName);
        $repository = $this->repositoryFactory->build($moderationContent);

        foreach ($moderationIds as $id) {
            $post = $repository->find($id);
            $moderation->apply($post);
        }
    }

    /**
     * @param Request $request
     */
    public function reverse(Request $request)
    {
        $moderationContent = $request->get('moderation_content');
        $moderationIds = $request->get('moderation_ids');
        $moderationName = $request->get('moderation_name');

        $moderation = $this->moderationRegistry->get($moderationName);

        if ($moderation instanceof ReversableModerationInterface) {
            $repository = $this->repositoryFactory->build($moderationContent);
            foreach ($moderationIds as $id) {
                $post = $repository->find($id);
                $moderation->reverse($post);
            }
        }
    }
}
