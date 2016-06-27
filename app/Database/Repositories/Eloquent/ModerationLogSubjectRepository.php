<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use MyBB\Core\Content\ContentInterface;
use MyBB\Core\Database\Models\ModerationLog;
use MyBB\Core\Database\Models\ModerationLogSubject;
use MyBB\Core\Database\Repositories\ModerationLogSubjectRepositoryInterface;

class ModerationLogSubjectRepository implements ModerationLogSubjectRepositoryInterface
{
    /**
     * @var ModerationLogSubject
     */
    protected $moderationLogSubject;

    /**
     * @param ModerationLogSubject $moderationLogSubject
     */
    public function __construct(ModerationLogSubject $moderationLogSubject)
    {
        $this->moderationLogSubject = $moderationLogSubject;
    }

    /**
     * @param int $id
     *
     * @return Model
     */
    public function find($id)
    {
        return $this->moderationLogSubject->find($id);
    }

    /**
     * @param ModerationLog $moderationLog
     * @param ContentInterface $content
     *
     * @return ModerationLogSubject
     */
    public function addContentToLog(ModerationLog $moderationLog, ContentInterface $content)
    {
        return $this->create([
            'moderation_log_id' => $moderationLog->id,
            'content_type'      => $content->getType(),
            'content_id'        => $content->getId(),
        ]);
    }

    /**
     * @param array $attributes
     *
     * @return ModerationLogSubject
     */
    public function create(array $attributes)
    {
        return $this->moderationLogSubject->create($attributes);
    }
}
