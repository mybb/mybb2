<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters;

use Illuminate\Contracts\View\Factory;
use McCool\LaravelAutoPresenter\{
    BasePresenter, PresenterDecorator
};
use MyBB\Core\Content\ContentInterface;
use MyBB\Core\Database\Models\{
    ModerationLog, User as UserModel
};
use MyBB\Core\Moderation\ModerationRegistry;
use MyBB\Core\Repository\RepositoryFactory;

class ModerationLogPresenter extends BasePresenter
{
    /**
     * @var ModerationRegistry
     */
    protected $moderationRegistry;

    /**
     * @var PresenterDecorator
     */
    private $presenterDecorator;

    /**
     * @var RepositoryFactory
     */
    private $repositoryFactory;

    /**
     * @var Factory
     */
    private $viewFactory;

    /**
     * @param object $resource
     * @param ModerationRegistry $moderationRegistry
     * @param PresenterDecorator $presenterDecorator
     * @param RepositoryFactory $repositoryFactory
     * @param Factory $viewFactory
     */
    public function __construct(
        $resource,
        ModerationRegistry $moderationRegistry,
        PresenterDecorator $presenterDecorator,
        RepositoryFactory $repositoryFactory,
        Factory $viewFactory
    ) {
        parent::__construct($resource);

        $this->moderationRegistry = $moderationRegistry;
        $this->presenterDecorator = $presenterDecorator;
        $this->repositoryFactory = $repositoryFactory;
        $this->viewFactory = $viewFactory;
    }

    /**
     * @return ModerationLog
     */
    public function getWrappedObject()
    {
        return parent::getWrappedObject();
    }

    /**
     * @return string
     */
    public function description() : string
    {
        $moderation = $this->moderationRegistry->get($this->getWrappedObject()->moderation);
        $moderation = $this->presenterDecorator->decorate($moderation);
        $content = $this->getContentForSubjects($this->getWrappedObject()->subjects()->get()->all());

        if ($this->getWrappedObject()->is_reverse) {
            $description = $moderation->reverseDescribe(
                $content,
                $this->getSourceFromLog($this->getWrappedObject()),
                $this->getDestinationFromLog($this->getWrappedObject())
            );
        } else {
            $description = $moderation->describe(
                $content,
                $this->getSourceFromLog($this->getWrappedObject()),
                $this->getDestinationFromLog($this->getWrappedObject())
            );
        }

        return $this->getUserProfileLink($this->getWrappedObject()->user) . ' ' . $description;
    }

    /**
     * @param array $subjects
     *
     * @return ContentInterface[]
     */
    private function getContentForSubjects(array $subjects)
    {
        $content = [];

        foreach ($subjects as $subject) {
            $repository = $this->repositoryFactory->build($subject->content_type);
            $content[] = $repository->find($subject->content_id);
        }

        return $content;
    }

    /**
     * @param ModerationLog $log
     *
     * @return ContentInterface
     */
    private function getDestinationFromLog(ModerationLog $log)
    {
        if ($log->destination_content_type && $log->destination_content_id) {
            $repository = $this->repositoryFactory->build($log->destination_content_type);

            if ($repository) {
                return $repository->find($log->destination_content_id);
            }
        }

        return null;
    }

    /**
     * @param ModerationLog $log
     *
     * @return ContentInterface
     */
    private function getSourceFromLog(ModerationLog $log)
    {
        if ($log->source_content_type && $log->source_content_id) {
            $repository = $this->repositoryFactory->build($log->source_content_type);

            if ($repository) {
                return $repository->find($log->source_content_id);
            }
        }

        return null;
    }

    /**
     * @param UserModel $user
     *
     * @return string
     */
    private function getUserProfileLink(UserModel $user) : string
    {
        return $this->viewFactory->make('user.profile_link', [
            'user'          => $user,
            'useStyledName' => true,
        ])->render();
    }
}
