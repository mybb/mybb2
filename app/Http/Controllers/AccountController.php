<?php namespace MyBB\Core\Http\Controllers;

use Illuminate\Auth\Guard;
use Illuminate\Http\Request;

class AccountController extends Controller {
    /** @var Guard $guard */
    private $guard;

	/**
	 * Create a new controller instance.
	 *
     * @param Guard $guard
	 */
	public function __construct(Guard $guard)
	{
        $this->guard = $guard;
	}

    public function index()
	{
		return view('account.dashboard')->withActive('dashboard');
	}

    public function getProfile()
    {
        return view('account.profile')->withActive('profile');
    }

    public function getNotifications()
    {
        return view('account.notifications')->withActive('notifications');
    }

    public function getFollowing()
    {
        return view('account.following')->withActive('following');
    }

    public function getBuddies()
    {
        return view('account.buddies')->withActive('buddies');
    }

    public function getPreferences()
    {
        return view('account.preferences')->withActive('preferences');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postPreferences(Request $request)
    {
        $this->validate($request, [
            'dst' => 'required|in:0,1,2',
            'follow_started_topics' => 'boolean',
            'follow_replies_topics' => 'boolean',
            'show_editor' => 'boolean',
            'topics_per_page' => 'integer|min:5|max:50',
            'posts_per_page' => 'integer|min:5|max:50',
            'style' => '', // exists:styles
            'language' => 'required', // test whether exists?
            'notify_on_like' => 'boolean',
            'notify_on_quote' => 'boolean',
            'notify_on_reply' => 'boolean',
            'notify_on_new_post' => 'boolean',
            'notify_on_new_comment' => 'boolean',
            'notify_on_comment_like' => 'boolean',
            'notify_on_my_comment_like' => 'boolean',
            'notify_on_comment_reply' => 'boolean',
            'notify_on_my_comment_reply' => 'boolean',
            'notify_on_new_message' => 'boolean',
            'notify_on_reply_message' => 'boolean',
            'notify_on_group_request' => 'boolean',
            'notify_on_moderation_post' => 'boolean',
            'notify_on_report' => 'boolean',
            'notify_on_username_change' => 'boolean',
            'notification_mails' => 'required|in:0,1,2',
        ]);

        $input = $request->except(['_token']);
        if($input['style'] == 'default')
            $input['style'] = null;
        if($input['language'] == 'default')
            $input['language'] = null;

        $input['follow_started_topics'] = isset($input['follow_started_topics']);
        $input['follow_replied_topics'] = isset($input['follow_replied_topics']);
        $input['show_editor'] = isset($input['show_editor']);
        $input['notify_on_like'] = isset($input['notify_on_like']);
        $input['notify_on_quote'] = isset($input['notify_on_quote']);
        $input['notify_on_reply'] = isset($input['notify_on_reply']);
        $input['notify_on_new_post'] = isset($input['notify_on_new_post']);
        $input['notify_on_new_comment'] = isset($input['notify_on_new_comment']);
        $input['notify_on_comment_like'] = isset($input['notify_on_comment_like']);
        $input['notify_on_my_comment_like'] = isset($input['notify_on_my_comment_like']);
        $input['notify_on_comment_reply'] = isset($input['notify_on_comment_reply']);
        $input['notify_on_my_comment_reply'] = isset($input['notify_on_my_comment_reply']);
        $input['notify_on_new_message'] = isset($input['notify_on_new_message']);
        $input['notify_on_reply_message'] = isset($input['notify_on_reply_message']);
        $input['notify_on_group_request'] = isset($input['notify_on_group_request']);
        $input['notify_on_moderation_post'] = isset($input['notify_on_moderation_post']);
        $input['notify_on_report'] = isset($input['notify_on_report']);
        $input['notify_on_username_change'] = isset($input['notify_on_username_change']);

        $this->guard->user()->settings->update($input);

        return redirect()->route('account.preferences');
    }

    public function getPrivacy()
    {
        return view('account.privacy')->withActive('privacy');
    }

    public function getDrafts()
    {
        return view('account.drafts')->withActive('drafts');
    }
}
