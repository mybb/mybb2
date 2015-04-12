<?php

namespace MyBB\Core\Twig\Extensions;

use MyBB\Core\Moderation\ReversibleModerationInterface;

class Moderation extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'MyBB_Twig_Extensions_Moderation';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('is_reversible_moderation', [$this, 'isReversibleModeration']),
        ];
    }

    /**
     * @param $moderation
     *
     * @return bool
     */
    public function isReversibleModeration($moderation)
    {
        return $moderation instanceof ReversibleModerationInterface;
    }
}
