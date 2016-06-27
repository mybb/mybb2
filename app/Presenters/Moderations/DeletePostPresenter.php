<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters\Moderations;

use MyBB\Core\Moderation\Moderations\DeletePost;

class DeletePostPresenter extends AbstractModerationPresenter implements ModerationPresenterInterface
{
    /**
     * @return DeletePost
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
        return 'fa-trash-o';
    }

    /**
     * @return string
     */
    protected function getDescriptionView()
    {
        return 'partials.moderation.logs.delete';
    }
}
