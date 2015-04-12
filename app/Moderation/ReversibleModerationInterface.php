<?php

namespace MyBB\Core\Moderation;

interface ReversibleModerationInterface extends ModerationInterface
{
    /**
     * @param mixed $content
     *
     * @return mixed
     */
    public function reverse($content);

    /**
     * @return string
     */
    public function getReverseDescription();

    /**
     * @return string
     */
    public function getReverseIcon();
}
