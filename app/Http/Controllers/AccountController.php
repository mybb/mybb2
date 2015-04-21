<?php namespace MyBB\Core\Http\Controllers;

use Hash;
use Illuminate\Auth\Guard;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;
use MyBB\Core\Database\Repositories\ProfileFieldGroupRepositoryInterface;
use MyBB\Core\Database\Repositories\UserProfileFieldRepositoryInterface;
use MyBB\Core\Http\Requests\Account\UpdateProfileRequest;
use MyBB\Core\Services\ConfirmationManager;
use MyBB\Settings\Store;
use Session;

class AccountController extends AbstractController
{
	/**
	 * @var Guard
	 */
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

	/**
	 * @return mixed
	 */
	public function index()
	{
		return view('account.dashboard')->withActive('dashboard');
	}

	/**
	 * @param ProfileFieldGroupRepositoryInterface $profileFieldGroups
	 *
	 * @return mixed
	 */
	public function getProfile(ProfileFieldGroupRepositoryInterface $profileFieldGroups)
	{
		$dob = explode('-', $this->guard->user()->dob);

		$dob = [
			'day' => $dob[0],
			'month' => $dob[1],
			'year' => $dob[2],
		];

		return view('account.profile', [
			'dob' => $dob,
			'profile_field_groups' => $profileFieldGroups->getAll()
		])->withActive('profile');
	}

	/**
	 * @param UpdateProfileRequest                $request
	 * @param UserProfileFieldRepositoryInterface $userProfileFields
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postProfile(UpdateProfileRequest $request, UserProfileFieldRepositoryInterface $userProfileFields)
	{
		// handle updates to the user model
		$input = $request->only(['usertitle']);
		$input['dob'] = $request->get('date_of_birth_day') .
			'-' . $request->get('date_of_birth_month') .
			'-' . $request->get('date_of_birth_year');
		$this->guard->user()->update($input);

		// handle profile field updates
		$profileFieldData = $request->get('profile_fields');
		foreach ($request->getProfileFields() as $profileField) {
			$userProfileFields->updateOrCreate(
				$this->guard->user(),
				$profileField,
				$profileFieldData[$profileField->id]
			);
		}

		return redirect()->route('account.profile')->withSuccess(trans('account.saved_profile'));
	}

	/**
	 * @return mixed
	 */
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

		if ($this->guard->getProvider()->validateCredentials($this->guard->user(), $request->only('password'))) {
			// Valid password so update
			$this->guard->user()->update($request->only('name'));

			return redirect()->route('account.profile')->withSuccess(trans('account.saved_username'));
		}

		return redirect()
			->route('account.username')
			->withInput($request->only('name'))
			->withErrors([
				'name' => trans('member.invalidCredentials'),
			]);
	}

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		return view('account.email')->withActive('profile')->withHasConfirmation(ConfirmationManager::has(
			'email',
			$this->guard->user()
		));
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

		if ($this->guard->getProvider()->validateCredentials($this->guard->user(), $request->only('password'))) {
			ConfirmationManager::send(
				'email',
				$this->guard->user(),
				'account.email.confirm',
				$request->get('email'),
				$request->only('email')
			);

			return redirect()->route('account.profile')->withSuccess(trans('account.confirmEmail'));
		}

		return redirect()
			->route('account.email')
			->withInput($request->only('email'))
			->withErrors([
				'email' => trans('member.invalidCredentials'),
			]);
	}

	/**
	 * @param string $token
	 *
	 * @return $this
	 */
	public function confirmEmail($token)
	{
		$email = ConfirmationManager::get('email', $token);

		if ($email === false) {
			return redirect()
				->route('account.profile')
				->withErrors([
					'token' => trans('confirmation.invalidToken'),
				]);
		}

		$this->guard->user()->update(['email' => $email]);

		return redirect()->route('account.profile')->withSuccess(trans('account.updatedEmail'));
	}

	/**
	 * @return mixed
	 */
	public function getPassword()
	{
		return view('account.password')->withActive('profile')->withHasConfirmation(ConfirmationManager::has(
			'password',
			$this->guard->user()
		));
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

		if ($this->guard->getProvider()->validateCredentials($this->guard->user(), $request->only('password'))) {
			// Don't save the password in plaintext!
			ConfirmationManager::send(
				'password',
				$this->guard->user(),
				'account.password.confirm',
				Hash::make($request->get('password1'))
			);

			return redirect()->route('account.profile')->withSuccess(trans('account.confirm'));
		}

		return redirect()
			->route('account.password')
			->withInput($request->only('password1'))
			->withErrors([
				'password1' => trans('member.invalidCredentials'),
			]);
	}

	/**
	 * @param string $token
	 *
	 * @return $this
	 */
	public function confirmPassword($token)
	{
		$password = ConfirmationManager::get('password', $token);

		if ($password === false) {
			return redirect()
				->route('account.profile')
				->withErrors([
					'token' => trans('confirmation.invalidToken'),
				]);
		}

		// Valid password so update
		$this->guard->user()->update(['password' => $password]);

		return redirect()->route('account.profile')->withSuccess(trans('account.updatedPassword'));
	}

	/**
	 * @return mixed
	 */
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
		if ($request->hasFile('avatar_file')) {
			$file = $request->file('avatar_file');

			$name = "avatar_{$this->guard->user()->id}_" . time() . "." . $file->getClientOriginalExtension();
			$file->move(public_path('uploads/avatars'), $name);
			$this->guard->user()->update(['avatar' => $name]);
		} // URL? Email?
		elseif (filter_var(
			$request->get('avatar_link'),
			FILTER_VALIDATE_URL
		) !== false || filter_var(
			$request->get('avatar_link'),
			FILTER_VALIDATE_EMAIL
		) !== false
		) {
			//$url = str_replace(array('http://', 'https://', 'ftp://'), '', strtolower($value));
			//return checkdnsrr($url, 'A');

			$this->guard->user()->update(['avatar' => $request->get('avatar_link')]);
		} else {
			// Nothing we want here, empty it!
			$this->guard->user()->update(['avatar' => '']);
		}

		return redirect()->route('account.profile')->withSuccess('account.saved_avatar');
	}

	/**
	 * @return mixed
	 */
	public function removeAvatar()
	{
		// TODO: Delete the old file if an uploaded was used
		$this->guard->user()->update(['avatar' => '']);

		return redirect()->route('account.profile')->withSuccess('account.removed_avatar');
	}

	/**
	 * @return mixed
	 */
	public function getNotifications()
	{
		return view('account.notifications')->withActive('notifications');
	}

	/**
	 * @return mixed
	 */
	public function getFollowing()
	{
		return view('account.following')->withActive('following');
	}

	/**
	 * @return mixed
	 */
	public function getBuddies()
	{
		return view('account.buddies')->withActive('buddies');
	}

	/**
	 * @param Store      $settings
	 * @param Filesystem $files
	 * @param Translator $trans
	 *
	 * @return mixed
	 */
	public function getPreferences(Store $settings, Filesystem $files, Translator $trans)
	{
		// Build the language array used by the select box
		$defaultLocale = $settings->get('user.language', 'en', false);

		$languages['default'] = trans('account.usedefault') . " - " . trans('general.language', [], '', $defaultLocale);

		$dirs = $files->directories(base_path('resources/lang/'));
		foreach ($dirs as $dir) {
			$lang = substr($dir, strrpos($dir, DIRECTORY_SEPARATOR) + 1);
			if ($trans->has('general.language', $lang)) {
				$languages[$lang] = trans('general.language', [], '', $lang);
			}
		}

		$selectedLanguage = $settings->get('user.language', 'en');
		if ($selectedLanguage == $defaultLocale) {
			$selectedLanguage = 'default';
		}

		// Build the timezone array
		$timezones = \DateTimeZone::listIdentifiers();
		$selectTimezones = [];
		foreach ($timezones as $tz) {
			$selectTimezones[$tz] = $tz;
		}

		$timezone = $settings->get('user.timezone', 'default');
		if ($timezone == 'default') {
			$timezone = trans('general.timezone');
		}

		return view(
			'account.preferences',
			compact('languages', 'selectedLanguage', 'selectTimezones', 'timezone')
		)->withActive('preferences');
	}

	/**
	 * @param Request    $request
	 * @param Store      $settings
	 * @param Translator $trans
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postPreferences(Request $request, Store $settings, Translator $trans)
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

		// Make checkboxes booleans
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

		// Unset non existant language and default (don't override the board default)
		if ($input['language'] == 'default' || !$trans->has('general.language', $input['language'])) {
			$input['language'] = null;
		}

		if ($input['date_format'] == 'default') {
			$input['date_format'] = null;
		}

		$timezones = \DateTimeZone::listIdentifiers();
		if ($input['timezone'] == trans('general.timezone') || !in_array($input['timezone'], $timezones)) {
			$input['timezone'] = null;
		}

		if ($input['time_format'] == trans('general.timeformat')) {
			$input['time_format'] = null;
		}

		// Prefix all settings with "user."
		$modifiedSettings = [];
		foreach ($input as $key => $value) {
			$modifiedSettings["user.{$key}"] = $value;
		}

		$settings->set($modifiedSettings, null, true);

		return redirect()->route('account.preferences')->withSuccess(trans('account.saved_preferences'));
	}

	/**
	 * @return mixed
	 */
	public function getPrivacy()
	{
		return view('account.privacy')->withActive('privacy');
	}

	/**
	 * @param Request $request
	 * @param Store   $settings
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postPrivacy(Request $request, Store $settings)
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

		// Prefix all settings with "user."
		$modifiedSettings = [];
		foreach ($input as $key => $value) {
			$modifiedSettings["user.{$key}"] = $value;
		}

		$settings->set($modifiedSettings, null, true);

		return redirect()->route('account.privacy')->withSuccess(trans('account.saved_privacy'));
	}

	/**
	 * @return mixed
	 */
	public function getDrafts()
	{
		return view('account.drafts')->withActive('drafts');
	}
}
