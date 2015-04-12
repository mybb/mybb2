<?php

namespace MyBB\Core\Moderation;

interface ModerationInterface
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getIcon();

    /**
     * @param mixed $content
     * @param array $options
     *
     * @return mixed
     */
    public function apply($content, array $options = []);

    /**
     * @param mixed $content
     * @param array $options
     *
     * @return bool
     */
    public function supports($content, array $options = []);
}
