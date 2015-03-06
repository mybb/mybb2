<?php namespace MyBB\Core\Http\Controllers;

use Hash;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use MyBB\Core\Services\ConfirmationManager;
use Session;

class AccountController extends Controller
{
	/** @var Guard $guard */
	private $guard;

	/**
	 * Create a new controller instance.
	 *
	 * @param Guard $guard
	 * @param Request $request
	 */
	public function __construct(Guard $guard, Request $request)
	{
		parent::__construct($guard, $request);

		$this->guard = $guard;
	}

	public function index()
	{
		return view('account.dashboard')->withActive('dashboard');
	}

	public function getProfile()
	{
		$dob = explode('-', $this->guard->user()->dob);

		$dob = [
			'day' => $dob[0],
			'month' => $dob[1],
			'year' => $dob[2],
		];

		return view('account.profile', compact('dob'))->withActive('profile');
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postProfile(Request $request)
	{
		$this->validate($request, [
			'date_of_birth_day' => 'integer|min:1|max:31',
			'date_of_birth_month' => 'integer|min:1|max:12',
			'date_of_birth_year' => 'integer',
			'usertitle' => 'string',
		]);

		$input = $request->only(['usertitle']);

		$input['dob'] = $request->get('date_of_birth_day') . '-' . $request->get('date_of_birth_month') . '-' . $request->get('date_of_birth_year');

		$this->guard->user()->update($input);

		return redirect()->route('account.profile');
	}

	public function getUsername()
	{
		return view('account.username')->withActive('profile');
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postUsername(Request $request)
	{
		$this->failedValidationRedirect = route('account.username');

		$this->validate($request, [
			'name' => 'required|max:255|unique:users',
			'password' => 'required',
		]);

		if($this->guard->getProvider()->validateCredentials($this->guard->user(), $request->only('password')))
		{
			// Valid password so update
			$this->guard->user()->update($request->only('name'));

			return redirect()->route('account.profile');
		}

		return redirect()
			->route('account.username')
			->withInput($request->only('name'))
			->withErrors([
				             'name' => trans('member.invalidCredentials'),
			             ]);
	}

	public function getEmail()
	{
		return view('account.email')->withActive('profile')->withHasConfirmation(ConfirmationManager::has('email',
		                                                                                                  $this->guard->user()));
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postEmail(Request $request)
	{
		$this->failedValidationRedirect = route('account.email');

		$this->validate($request, [
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required',
		]);

		if($this->guard->getProvider()->validateCredentials($this->guard->user(), $request->only('password')))
		{
			ConfirmationManager::send('email', $this->guard->user(), 'account.email.confirm', $request->get('email'),
			                          $request->only('email'));

			// We need show some sort of feedback to the user
			Session::flash('success', trans('account.confirmEmail'));

			return redirect()->route('account.profile');
		}

		return redirect()
			->route('account.email')
			->withInput($request->only('email'))
			->withErrors([
				             'email' => trans('member.invalidCredentials'),
			             ]);
	}

	public function confirmEmail($token)
	{
		$email = ConfirmationManager::get('email', $token);

		if($email === false)
		{
			return redirect()
				->route('account.profile')
				->withErrors([
					             'token' => trans('confirmation.invalidToken'),
				             ]);
		}

		$this->guard->user()->update(['email' => $email]);

		// We need show some sort of feedback to the user
		Session::flash('success', trans('account.updatedEmail'));

		return redirect()->route('account.profile');
	}

	public function getPassword()
	{
		return view('account.password')->withActive('profile')->withHasConfirmation(ConfirmationManager::has('password',
		                                                                                                     $this->guard->user()));
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postPassword(Request $request)
	{
		$this->failedValidationRedirect = route('account.password');

		$this->validate($request, [
			'password1' => 'required|min:6',
			'password' => 'required',
		]);

		if($this->guard->getProvider()->validateCredentials($this->guard->user(), $request->only('password')))
		{
			// Don't save the password in plaintext!
			ConfirmationManager::send('password', $this->guard->user(), 'account.password.confirm',
			                          Hash::make($request->get('password1')));

			// We need show some sort of feedback to the user
			Session::flash('success', trans('account.confirm'));

			return redirect()->route('account.profile');
		}

		return redirect()
			->route('account.password')
			->withInput($request->only('password1'))
			->withErrors([
				             'password1' => trans('member.invalidCredentials'),
			             ]);
	}

	public function confirmPassword($token)
	{
		$password = ConfirmationManager::get('password', $token);

		if($password === false)
		{
			return redirect()
				->route('account.profile')
				->withErrors([
					             'token' => trans('confirmation.invalidToken'),
				             ]);
		}

		// Valid password so update
		$this->guard->user()->update(['password' => $password]);

		// We need show some sort of feedback to the user
		Session::flash('success', trans('account.updatedPassword'));

		return redirect()->route('account.profile');
	}

	public function getAvatar()
	{
		return view('account.avatar')->withActive('profile');
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postAvatar(Request $request)
	{
		$this->failedValidationRedirect = route('account.avatar');

		// TODO: validation. Upload size, valid link, valid email
		$this->validate($request, [
			'avatar_file' => 'image',
		]);

		// TODO: Delete the old file if an uploaded was used

		// File?
		if($request->hasFile('avatar_file'))
		{
			$file = $request->file('avatar_file');

			$name = "avatar_{$this->guard->user()->id}_" . time() . "." . $file->getClientOriginalExtension();
			$file->move(public_path('uploads/avatars'), $name);
			$this->guard->user()->update(['avatar' => $name]);
		} // URL? Email?
		elseif(filter_var($request->get('avatar_link'),
		                  FILTER_VALIDATE_URL) !== false || filter_var($request->get('avatar_link'),
		                                                               FILTER_VALIDATE_EMAIL) !== false
		)
		{
			//$url = str_replace(array('http://', 'https://', 'ftp://'), '', strtolower($value));
			//return checkdnsrr($url, 'A');

			$this->guard->user()->update(['avatar' => $request->get('avatar_link')]);
		} else
		{
			// Nothing we want here, empty it!
			$this->guard->user()->update(['avatar' => '']);
		}

		return redirect()->route('account.profile');
	}

	public function removeAvatar()
	{
		// TODO: Delete the old file if an uploaded was used
		$this->guard->user()->update(['avatar' => '']);

		return redirect()->route('account.profile');
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
	 *
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
		{
			$input['style'] = null;
		}
		if($input['language'] == 'default')
		{
			$input['language'] = null;
		}

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

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postPrivacy(Request $request)
	{
		$this->validate($request, [
			'showonline' => 'boolean',
			'receive_messages' => 'boolean',
			'block_blocked_messages' => 'boolean',
			'hide_blocked_posts' => 'boolean',
			'only_buddy_messages' => 'boolean',
			'receive_email' => 'boolean',
			'dob_privacy' => 'required|in:0,1,2',
			'dob_visibility' => 'required|in:0,1,2',
		]);

		$input = $request->except(['_token']);

		$input['showonline'] = isset($input['showonline']);
		$input['receive_messages'] = isset($input['receive_messages']);
		$input['block_blocked_messages'] = isset($input['block_blocked_messages']);
		$input['hide_blocked_posts'] = isset($input['hide_blocked_posts']);
		$input['only_buddy_messages'] = isset($input['only_buddy_messages']);
		$input['receive_email'] = isset($input['receive_email']);

		$this->guard->user()->settings->update($input);

		return redirect()->route('account.privacy');
	}

	public function getDrafts()
	{
		return view('account.drafts')->withActive('drafts');
	}
}
