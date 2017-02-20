<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use MyBB\Core\Database\Models\ModerationLog;
use MyBB\Core\Database\Repositories\ModerationLogRepositoryInterface;

class ModerationLogRepository implements ModerationLogRepositoryInterface
{
    /**
     * @var ModerationLog
     */
    protected $moderationLog;

    /**
     * @param ModerationLog $moderationLog
     */
    public function __construct(ModerationLog $moderationLog)
    {
        $this->moderationLog = $moderationLog;
    }

    /**
     * @param int $id
     *
     * @return ModerationLog
     */
    public function find(int $id) : ModerationLog
    {
        return $this->moderationLog->find($id);
    }

    /**
     * @param array $attributes
     *
     * @return ModerationLog
     */
    public function create(array $attributes) : ModerationLog
    {
        return $this->moderationLog->create($attributes);
    }
}
