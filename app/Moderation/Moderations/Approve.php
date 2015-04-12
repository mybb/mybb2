<?php

namespace MyBB\Core\Moderation\Moderations;

use MyBB\Core\Moderation\ReversibleModerationInterface;

class Approve implements ReversibleModerationInterface
{
    /**
     * @param ApprovableInterface $approvable
     *
     * @return mixed
     */
    public function approve(ApprovableInterface $approvable)
    {
        return $approvable->approve();
    }

    /**
     * @param ApprovableInterface $approvable
     *
     * @return mixed
     */
    public function unapprove(ApprovableInterface $approvable)
    {
        return $approvable->unapprove();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'approve';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Approve';
    }

    /**
     * @param mixed $content
     *
     * @return mixed
     */
    public function apply($content)
    {
        if ($this->supports($content)) {
            return $this->approve($content);
        }
    }

    /**
     * @param mixed $content
     *
     * @return bool
     */
    public function supports($content)
    {
        return $content instanceof ApprovableInterface;
    }

    /**
     * @param mixed $content
     *
     * @return mixed
     */
    public function reverse($content)
    {
        if ($this->supports($content)) {
            return $this->unapprove($content);
        }
    }

    /**
     * @return string
     */
    public function getReverseDescription()
    {
        return 'Unapprove';
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'fa-check';
    }

    /**
     * @return string
     */
    public function getReverseIcon()
    {
        return 'fa-minus';
    }
}
