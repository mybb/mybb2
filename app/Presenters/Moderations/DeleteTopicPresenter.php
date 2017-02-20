<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters\Moderations;

use MyBB\Core\Moderation\Moderations\DeleteTopic;

class DeleteTopicPresenter extends AbstractModerationPresenter implements ModerationPresenterInterface
{
    /**
     * @return DeleteTopic
     */
    public function getWrappedObject()
    {
        return parent::getWrappedObject();
    }

    /**
     * @return string
     */
    public function icon() : string
    {
        return 'fa-trash-o';
    }

    /**
     * @return string
     */
    protected function getDescriptionView() : string
    {
        return 'partials.moderation.logs.delete';
    }
}
