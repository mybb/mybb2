<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Warnings;

use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Core\Exceptions\UserNotFoundException;

class WarningsUsers implements WarnableContentInterface
{

    /**
     * @var UserRepositoryInterface
     */
    private $contentRepository;

    /**
     * WarningsPosts constructor.
     * @param UserRepositoryInterface $contentRepository
     */
    public function __construct(UserRepositoryInterface $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function getWarningContent($contentId)
    {
        $user = $this->contentRepository->find($contentId);
        if (!$user) {
            throw new UserNotFoundException;
        }

        $content = [
            'user_id' => $user->id,
            'content' => null,
        ];

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getWarningPreviewView($content)
    {
        return false;
    }
}
