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

class Close implements ReversibleModerationInterface, HasPresenter, SourceableInterface
{
    /**
     * @return string
     */
    public function getKey() : string
    {
        return 'close';
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return 'moderation.moderate.close';
    }

    /**
     * @param CloseableInterface $closeable
     *
     * @return mixed
     */
    public function close(CloseableInterface $closeable)
    {
        return $closeable->close();
    }

    /**
     * @param CloseableInterface $closeable
     *
     * @return mixed
     */
    public function open(CloseableInterface $closeable)
    {
        return $closeable->open();
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
            return $this->close($content);
        }
    }

    /**
     * @param mixed $content
     * @param array $options
     *
     * @return bool
     */
    public function supports($content, array $options = []) : bool
    {
        return $content instanceof CloseableInterface;
    }

    /**
     * @param mixed $content
     *
     * @return bool
     */
    public function visible($content) : bool
    {
        return $content instanceof CloseableInterface;
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
            return $this->open($content);
        }
    }

    /**
     * @return string
     */
    public function getReverseName() : string
    {
        return 'moderation.moderate.open';
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass() : string
    {
        return 'MyBB\Core\Presenters\Moderations\ClosePresenter';
    }

    /**
     * @return string
     */
    public function getPermissionName() : string
    {
        return 'canClose';
    }
}
