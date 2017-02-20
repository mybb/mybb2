<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Moderation\Logger;

use Illuminate\Support\Collection;
use MyBB\Core\Content\ContentInterface;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Repositories\ModerationLogRepositoryInterface;
use MyBB\Core\Database\Repositories\ModerationLogSubjectRepositoryInterface;
use MyBB\Core\Http\Requests\Moderation\ModerationRequest;
use MyBB\Core\Moderation\ModerationInterface;

class DatabaseLogger implements ModerationLoggerInterface
{
    /**
     * @var ModerationLogRepositoryInterface
     */
    protected $moderationLogRepository;

    /**
     * @var ModerationLogSubjectRepositoryInterface
     */
    protected $moderationLogSubjectRepository;

    /**
     * @param ModerationLogRepositoryInterface $moderationLogRepository
     * @param ModerationLogSubjectRepositoryInterface $moderationLogSubjectRepository
     */
    public function __construct(
        ModerationLogRepositoryInterface $moderationLogRepository,
        ModerationLogSubjectRepositoryInterface $moderationLogSubjectRepository
    ) {
        $this->moderationLogRepository = $moderationLogRepository;
        $this->moderationLogSubjectRepository = $moderationLogSubjectRepository;
    }

    /**
     * @param User $user
     * @param ModerationInterface $moderation
     * @param Collection $contentCollection
     * @param string $ipAddress
     * @param bool $isReverse
     * @param ContentInterface $source
     * @param ContentInterface $destination
     */
    public function log(
        User $user,
        ModerationInterface $moderation,
        Collection $contentCollection,
        string $ipAddress,
        bool $isReverse = false,
        ContentInterface $source = null,
        ContentInterface $destination = null
    ) {
        $attributes = [
            'user_id'    => $user->id,
            'moderation' => $moderation->getKey(),
            'is_reverse' => $isReverse,
            'ip_address' => $ipAddress,
        ];

        if ($source) {
            $attributes['source_content_type'] = $source->getType();
            $attributes['source_content_id'] = $source->getId();
        }

        if ($destination) {
            $attributes['destination_content_type'] = $destination->getType();
            $attributes['destination_content_id'] = $destination->getId();
        }

        $moderationLog = $this->moderationLogRepository->create($attributes);

        foreach ($contentCollection as $content) {
            $this->moderationLogSubjectRepository->addContentToLog($moderationLog, $content);
        }
    }

    /**
     * @param User $user
     * @param ModerationRequest $request
     */
    public function logFromRequest(User $user, ModerationRequest $request)
    {
        $this->log(
            $user,
            $request->getModeration(),
            new Collection($request->getModeratableContent()),
            $request->getClientIp(),
            false,
            $request->getSource(),
            $request->getDestination()
        );
    }

    /**
     * @param User $user
     * @param ModerationRequest $request
     */
    public function logReverseFromRequest(User $user, ModerationRequest $request)
    {
        $this->log(
            $user,
            $request->getModeration(),
            new Collection($request->getModeratableContent()),
            $request->getClientIp(),
            true,
            $request->getSource(),
            $request->getDestination()
        );
    }
}
