<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Content\ContentInterface;
use MyBB\Core\Database\Models\ModerationLog;
use MyBB\Core\Database\Models\ModerationLogSubject;

interface ModerationLogSubjectRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $attributes
     *
     * @return ModerationLogSubject
     */
    public function create(array $attributes) : ModerationLogSubject;

    /**
     * @param ModerationLog $moderationLog
     * @param ContentInterface $content
     *
     * @return ModerationLogSubject
     */
    public function addContentToLog(ModerationLog $moderationLog, ContentInterface $content) : ModerationLogSubject;
}
