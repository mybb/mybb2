<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;
use MyBB\Core\Database\Repositories\{
    ProfileFieldGroupRepositoryInterface, UserProfileFieldRepositoryInterface
};
use MyBB\Settings\Repositories\SettingRepositoryInterface;
use MyBB\Core\Http\Requests\Account\{
    CropAvatarRequest, UpdateAvatarRequest, UpdateEmailRequest, UpdatePasswordRequest, UpdatePreferencesRequest, UpdatePrivacyRequest, UpdateProfileRequest, UpdateUsernameRequest
};
use MyBB\Core\Services\ConfirmationManager;
use MyBB\Settings\Store;

class AccountController extends AbstractController
{
    /**
     * @var Guard
     */
    private $guard;

    /**
     * @var SettingRepositoryInterface
     */
    private $settingRepository;

    /**
     * Create a new controller instance.
     *
     * @param Guard $guard
     * @param SettingRepositoryInterface $settingRepository
     */
    public function __construct(Guard $guard, SettingRepositoryInterface $settingRepository)
    {
        $this->guard = $guard;
        $this->settingRepository = $settingRepository;
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
            'day'   => $dob[0],
            'month' => $dob[1],
            'year'  => $dob[2],
        ];

        return view('account.profile', [
            'dob'                  => $dob,
            'profile_field_groups' => $profileFieldGroups->getAll(),
        ])->withActive('profile');
    }

    /**
     * @param UpdateProfileRequest $request
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
     * @param UpdateUsernameRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUsername(UpdateUsernameRequest $request)
    {
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
     * @param UpdateEmailRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEmail(UpdateEmailRequest $request)
    {
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail(string $token)
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
     * @param UpdatePasswordRequest $request
     * @param BcryptHasher $hasher
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postPassword(UpdatePasswordRequest $request, BcryptHasher $hasher)
    {
        if ($this->guard->getProvider()->validateCredentials($this->guard->user(), $request->only('password'))) {
            // Don't save the password in plaintext!
            ConfirmationManager::send(
                'password',
                $this->guard->user(),
                'account.password.confirm',
                $hasher->make($request->get('password1'))
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
    public function confirmPassword(string $token)
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
     * @param UpdateAvatarRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAvatar(UpdateAvatarRequest $request)
    {
        // TODO: Delete the old file if an uploaded was used

        // File?
        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');

            $name = "avatar_{$this->guard->user()->id}_" . time() . "." . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $name);
            $this->guard->user()->update(['avatar' => $name]);

            if ($request->get('ajax', false)) {
                return response()->json([
                    'needCrop' => true,
                    'avatar'   => asset("uploads/avatars/" . $name),
                ]);
            }
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

        return redirect()->route('account.profile')->withSuccess(trans('account.saved_avatar'));
    }

    /**
     * @param CropAvatarRequest $request
     *
     * @return mixed
     */
    public function postAvatarCrop(CropAvatarRequest $request)
    {
        $data = [
            'w'  => $request->get('w'),
            'h'  => $request->get('h'),
            'x'  => $request->get('x'),
            'y'  => $request->get('y'),
            'x2' => $request->get('x2'),
            'y2' => $request->get('y2'),
        ];

        $avatar = public_path("uploads/avatars/" . $this->guard->user()->avatar);
        if (!file_exists($avatar)) {
            return response()->json([
                'success' => false,
                'error'   => trans('account.avatar_upload'),
            ]);
        }
        $temp = explode('.', $avatar);
        $ext = $temp[count($temp) - 1];

        $image = imagecreatefromstring(@file_get_contents($avatar));
        list($width, $height) = getimagesize($avatar);

        if ($data['w'] > $width || $data['h'] > $height || $data['x2'] < $data['x'] || $data['y2'] < $data['y'] ||
            $data['w'] <= 0 || $data['h'] <= 0 || $data['x2'] - $data['x'] != $data['w'] ||
            $data['y2'] - $data['y'] != $data['h']
        ) {
            return response()->json([
                'success' => false,
                'error'   => trans('account.select_area_crop'),
            ]);
        }
        $dst_r = imagecreatetruecolor($data['w'], $data['h']);

        imagecopyresampled(
            $dst_r,
            $image,
            0,
            0,
            $data['x'],
            $data['y'],
            $data['w'],
            $data['h'],
            $data['w'],
            $data['h']
        );

        switch ($ext) {
            case 'bmp':
                imagewbmp($dst_r, $avatar);
                break;
            case 'gif':
                imagegif($dst_r, $avatar);
                break;
            case 'jpg':
            case 'jpeg':
                imagejpeg($dst_r, $avatar);
                break;
            case 'png':
            default:
                imagepng($dst_r, $avatar);
                break;
        }

        return response()->json([
            'success' => true,
            'message' => trans('account.saved_avatar'),
            'avatar'  => asset("uploads/avatars/" . $this->guard->user()->avatar),
        ]);
    }


    /**
     * @return mixed
     */
    public function removeAvatar()
    {
        // TODO: Delete the old file if an uploaded was used
        $this->guard->user()->update(['avatar' => '']);

        return redirect()->route('account.profile')->withSuccess(trans('account.removed_avatar'));
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
     * @param Store $settings
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
     * @param UpdatePreferencesRequest $request
     * @param Translator $trans
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postPreferences(UpdatePreferencesRequest $request, Translator $trans)
    {
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

        $modifiedSettings = [];

        $modifiedSettings['conversations.message_order'] = $input['message_order'];
        unset($input['message_order']);

        // Prefix all settings with "user."
        foreach ($input as $key => $value) {
            $modifiedSettings["user.{$key}"] = $value;
        }

        $user = $this->guard->user();
        $this->settingRepository->updateSettings($modifiedSettings, $user->getAuthIdentifier());

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
     * @param UpdatePrivacyRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postPrivacy(UpdatePrivacyRequest $request)
    {
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

        $user = $this->guard->user();
        $this->settingRepository->updateSettings($modifiedSettings, $user->getAuthIdentifier());

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
