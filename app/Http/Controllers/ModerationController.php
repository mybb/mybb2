<?php

namespace MyBB\Core\Http\Controllers;

use MyBB\Core\Http\Requests\Moderation\ModerationRequest;
use MyBB\Core\Http\Requests\Moderation\ReversibleModerationRequest;
use MyBB\Core\Moderation\ArrayModerationInterface;

class ModerationController extends Controller
{
    /**
     * @param ModerationRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function moderate(ModerationRequest $request)
    {
        $options = $request->getModerationOptions();
        $moderation = $request->getModeration();

        if ($moderation instanceof ArrayModerationInterface) {
            $moderation->apply($request->getModeratableContent(), $options);
        } else {
            foreach ($request->getModeratableContent() as $content) {
                $moderation->apply($content, $options);
            }
        }

        return redirect()->back();
    }

    /**
     * @param ReversibleModerationRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reverse(ReversibleModerationRequest $request)
    {
        $options = $request->getModerationOptions();
        foreach ($request->getModeratableContent() as $content) {
            $request->getModeration()->reverse($content, $options);
        }

        return redirect()->back();
    }

    /**
     * @param ModerationRequest $request
     * @param string $moderationName
     *
     * @return \Illuminate\View\View
     */
    public function form(ModerationRequest $request, $moderationName)
    {
        return view('partials.moderation.moderation_form', [
            'moderation' => $request->getModerationByName($moderationName),
            'moderation_content' => $request->get('moderation_content'),
            'moderation_ids' => $request->get('moderation_ids')
        ]);
    }
}
