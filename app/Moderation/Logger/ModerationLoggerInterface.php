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
use MyBB\Core\Http\Requests\Moderation\ModerationRequest;
use MyBB\Core\Moderation\ModerationInterface;

interface ModerationLoggerInterface
{
    /**
     * @param User $user
     * @param ModerationInterface $moderation
     * @param Collection $contentCollection
     * @param string $ipAddress
     * @param bool $isReverse
     * @param ContentInterface $source
     * @param ContentInterface $destination
     *
     * @return
     */
    public function log(
        User $user,
        ModerationInterface $moderation,
        Collection $contentCollection,
        string $ipAddress,
        bool $isReverse = false,
        ContentInterface $source = null,
        ContentInterface $destination = null
    );

    /**
     * @param User $user
     * @param ModerationRequest $request
     */
    public function logFromRequest(User $user, ModerationRequest $request);

    /**
     * @param User $user
     * @param ModerationRequest $request
     */
    public function logReverseFromRequest(User $user, ModerationRequest $request);
}
