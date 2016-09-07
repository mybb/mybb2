<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Moderation\Moderations;

use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Core\Moderation\ReversibleModerationInterface;
use MyBB\Core\Moderation\SourceableInterface;
use MyBB\Core\Presenters\Moderations\StickPresenter;

class Stick implements ReversibleModerationInterface, HasPresenter, SourceableInterface
{
    /**
     * @return string
     */
    public function getKey()
    {
        return 'stick';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'forum.moderate.stick';
    }

    /**
     * @param StickableInterface $stickable
     *
     * @return mixed
     */
    public function stick(StickableInterface $stickable)
    {
        return $stickable->stick();
    }

    /**
     * @param StickableInterface $stickable
     *
     * @return mixed
     */
    public function unstick(StickableInterface $stickable)
    {
        return $stickable->unstick();
    }

    /**
     * @param mixed $content
     * @param array $options
     *
     * @return mixed
     */
    public function apply($content, array $options = [])
    {
        if ($this->supports($content)) {
            return $this->stick($content);
        }
    }

    /**
     * @param mixed $content
     * @param array $options
     *
     * @return bool
     */
    public function supports($content, array $options = [])
    {
        return $content instanceof StickableInterface;
    }

    /**
     * @param mixed $content
     *
     * @return bool
     */
    public function visible($content)
    {
        return $content instanceof StickableInterface;
    }

    /**
     * @param mixed $content
     * @param array $options
     *
     * @return mixed
     */
    public function reverse($content, array $options = [])
    {
        if ($this->supports($content)) {
            return $this->unstick($content);
        }
    }

    /**
     * @return string
     */
    public function getReverseName()
    {
        return 'forum.moderate.unstick';
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return StickPresenter::class;
    }

    /**
     * @return string
     */
    public function getPermissionName()
    {
        return 'canStick';
    }
}
