<?php

namespace MyBB\Core\Moderation;

interface ModerationInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getIcon();

    /**
     * @param mixed $content
     *
     * @return mixed
     */
    public function apply($content);

    /**
     * @param mixed $content
     *
     * @return bool
     */
    public function supports($content);
}
