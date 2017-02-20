<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters\Moderations;

use MyBB\Core\Moderation\Moderations\Approve;

class ApprovePresenter extends AbstractReversibleModerationPresenter implements ReversibleModerationPresenterInterface
{
    /**
     * @return Approve
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
        return 'fa-check';
    }

    /**
     * @return string
     */
    public function reverseIcon() : string
    {
        return 'fa-minus';
    }

    /**
     * @return string
     */
    protected function getDescriptionView() : string
    {
        return 'partials.moderation.logs.approve';
    }

    /**
     * @return string
     */
    protected function getReverseDescriptionView() : string
    {
        return 'partials.moderation.logs.unapprove';
    }
}
