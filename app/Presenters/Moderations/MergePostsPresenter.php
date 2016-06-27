<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters\Moderations;

use MyBB\Core\Moderation\Moderations\MergePosts;

class MergePostsPresenter extends AbstractModerationPresenter implements ModerationPresenterInterface
{
    /**
     * @return MergePosts
     */
    public function getWrappedObject()
    {
        return parent::getWrappedObject();
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'fa-code-fork';
    }

    /**
     * @return string
     */
    protected function getDescriptionView()
    {
        return 'partials.moderation.logs.merge';
    }
}
