<?php

namespace MyBB\Core\Http\Controllers;

use MyBB\Core\Http\Requests\Moderation\ModerationRequest;
use MyBB\Core\Http\Requests\Moderation\ReversibleModerationRequest;

class ModerationController extends Controller
{
    /**
     * @param ModerationRequest $request
     */
    public function moderate(ModerationRequest $request)
    {
        foreach ($request->getModeratableContent() as $content) {
            $request->getModeration()->apply($content);
        }
    }

    /**
     * @param ReversibleModerationRequest $request
     */
    public function reverse(ReversibleModerationRequest $request)
    {
        foreach ($request->getModeratableContent() as $content) {
            $request->getModeration()->reverse($content);
        }
    }
}
