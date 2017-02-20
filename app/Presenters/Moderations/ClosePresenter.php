<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters\Moderations;

use MyBB\Core\Moderation\Moderations\Close;

class ClosePresenter extends AbstractReversibleModerationPresenter implements ReversibleModerationPresenterInterface
{
    /**
     * @return Close
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
        return 'fa-lock';
    }

    /**
     * @return string
     */
    public function reverseIcon() : string
    {
        return 'fa-unlock';
    }

    /**
     * @return string
     */
    protected function getDescriptionView() : string
    {
        return 'partials.moderation.logs.close';
    }

    /**
     * @return string
     */
    protected function getReverseDescriptionView() : string
    {
        return 'partials.moderation.logs.open';
    }
}
