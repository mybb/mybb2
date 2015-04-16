<?php

namespace MyBB\Core\Moderation;

use MyBB\Core\Registry\RegistryInterface;

class ModerationRegistry implements RegistryInterface
{
    /**
     * @var ModerationInterface[]
     */
    protected $moderations;

    /**
     * @param ModerationInterface[] $moderations
     */
    public function __construct(array $moderations = [])
    {
        foreach ($moderations as $moderation) {
            $this->addModeration($moderation);
        }
    }

    /**
     * @param ModerationInterface $moderation
     */
    public function addModeration(ModerationInterface $moderation)
    {
        $this->moderations[$moderation->getKey()] = $moderation;
    }

    /**
     * @param string $key
     *
     * @return ModerationInterface
     */
    public function get($key)
    {
        return $this->moderations[$key];
    }

    /**
     * @return ModerationInterface[]
     */
    public function getAll()
    {
        return $this->moderations;
    }

    /**
     * @param mixed $content
     *
     * @return ModerationInterface[]
     */
    public function getForContent($content)
    {
        $supportedModerations = [];

        foreach ($this->moderations as $moderation) {
            if ($moderation->visible($content)) {
                $supportedModerations[$moderation->getKey()] = $moderation;
            }
        }

        return $supportedModerations;
    }
}