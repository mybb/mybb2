<?php

namespace MyBB\Core\Http\Controllers;

use Illuminate\Http\Request;
use MyBB\Core\Database\Models\Post;

class ModerationController extends Controller
{
    public function moderate(Request $request)
    {
        $contentType = $request->get('content_type');
        $contentId = $request->get('content_id');
        $moderationName = $request->get('moderation_name');

        $moderation = app()->make('MyBB\Core\Moderation\ModerationRegistry')->get($moderationName);
        $post = Post::find($contentId);

        $moderation->apply($post);

        return redirect()->back();
    }
}
