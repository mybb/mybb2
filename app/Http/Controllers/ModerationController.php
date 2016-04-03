<?php

namespace MyBB\Core\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Database\Models\ModerationLog;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Http\Requests\Moderation\ModerationRequest;
use MyBB\Core\Http\Requests\Moderation\ReversibleModerationRequest;
use MyBB\Core\Moderation\ArrayModerationInterface;
use MyBB\Core\Moderation\Logger\ModerationLoggerInterface;

class ModerationController extends AbstractController
{
	/**
	 * @param ModerationRequest         $request
	 * @param ModerationLoggerInterface $moderationLogger
	 * @param Guard                     $guard
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function moderate(ModerationRequest $request, ModerationLoggerInterface $moderationLogger, Guard $guard)
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

		$moderationLogger->logFromRequest($guard->user(), $request);

		return redirect()->back();
	}

	/**
	 * @param ReversibleModerationRequest $request
	 *
	 * @param ModerationLoggerInterface   $moderationLogger
	 * @param Guard                       $guard
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function reverse(
		ReversibleModerationRequest $request,
		ModerationLoggerInterface $moderationLogger,
		Guard $guard
	) {
		$options = $request->getModerationOptions();
		foreach ($request->getModeratableContent() as $content) {
			$request->getModeration()->reverse($content, $options);
		}

		$moderationLogger->logReverseFromRequest($guard->user(), $request);

		return redirect()->back();
	}

	/**
	 * @param ModerationRequest $request
	 * @param string            $moderationName
	 *
	 * @return \Illuminate\View\View
	 */
	public function form(ModerationRequest $request, $moderationName)
	{
		return view('partials.moderation.moderation_form', [
			'moderation' => $request->getModerationByName($moderationName),
			'moderation_content' => $request->get('moderation_content'),
			'moderation_ids' => $request->get('moderation_ids'),
			'moderation_source_type' => $request->get('moderation_source_type'),
			'moderation_source_id' => $request->get('moderation_source_id'),
		]);
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function controlPanel()
	{
		return view('moderation.dashboard')->withActive('dashboard');
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function queue()
	{
		$topics = Topic::where('approved', 0)->get();
		$posts = Post::where('approved', 0)->get();

		return view('moderation.queue', [
			'queued_topics' => $topics,
			'queued_posts' => $posts
		])->withActive('queue');
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function logs()
	{
		$logs = ModerationLog::all()->reverse();
		return view('moderation.logs', ['logs' => $logs])->withActive('logs');
	}
}
