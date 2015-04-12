<?php

namespace MyBB\Core\Http\Controllers;

use Illuminate\Http\Request;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Moderation\ReversableModerationInterface;

class ModerationController extends Controller
{
    public function moderate(Request $request)
    {
        $moderationContent = $request->get('moderation_content');
        $moderationIds = $request->get('moderation_ids');
        $moderationName = $request->get('moderation_name');

        $moderation = app()->make('MyBB\Core\Moderation\ModerationRegistry')->get($moderationName);

        foreach ($moderationIds as $id) {
            $post = Post::find($id);
            $moderation->apply($post);
        }
    }

    public function reverse(Request $request)
    {
        $moderationContent = $request->get('moderation_content');
        $moderationIds = $request->get('moderation_ids');
        $moderationName = $request->get('moderation_name');

        $moderation = app()->make('MyBB\Core\Moderation\ModerationRegistry')->get($moderationName);

        if ($moderation instanceof ReversableModerationInterface) {
            foreach ($moderationIds as $id) {
                $post = Post::find($id);
                $moderation->reverse($post);
            }
        }
    }
}
