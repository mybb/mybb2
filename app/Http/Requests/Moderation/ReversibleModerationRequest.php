<?php

namespace MyBB\Core\Http\Requests\Moderation;

use MyBB\Core\Moderation\ReversibleModerationInterface;

class ReversibleModerationRequest extends ModerationRequest
{
    /**
     * @return ReversibleModerationInterface
     */
    public function getModeration()
    {
        $moderation = parent::getModeration();

        if ($moderation instanceof ReversibleModerationInterface) {
            return $moderation;
        }
    }
}
