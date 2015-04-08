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
        $this->moderations[$moderation->getName()] = $moderation;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function get($name)
    {
        return $this->moderations[$name];
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
            if ($moderation->supports($content)) {
                $supportedModerations[$moderation->getName()] = $moderation;
            }
        }

        return $supportedModerations;
    }
}
