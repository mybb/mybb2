<?php
/**
 * Extension for Twig to render a user profile link for a given user.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Twig\Extensions;

use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Presenters\UserPresenter;
use Twig_SimpleFunction;

class RenderUserProfileLink extends \Twig_Extension
{
    const NAME = 'MyBB_Twig_Extensions_RenderUserProfileLink';

    const FUNCTION_NAME = 'render_profile_link';

    /**
     * @var Guard $guard
     */
    private $guard;

    /**
     * @param Guard $guard
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() : string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(static::FUNCTION_NAME, [$this, 'renderProfileLink'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Render the profile link for a given user.
     *
     * @param User $user The user to render the profile link for
     *                            or null to render the link for the current user.
     * @param bool $includeAvatar Whether to include the user's avatar in the link.
     * @param bool $useStyledName Whether to apply the usergroup styling to the username.
     *
     * @return string The rendered profile link.
     */
    public function renderProfileLink(UserPresenter $user = null, bool $includeAvatar = false, bool $useStyledName = true) : string
    {
        if (is_null($user)) {
            $user = $this->guard->user();
        }

        return view('user.profile_link', compact('user', 'includeAvatar', 'useStyledName'))->render();
    }
}
