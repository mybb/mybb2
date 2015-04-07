<?php
/**
 * Extension for Twig to render a user profile link for a given user.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Twig\Extensions;

use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Presenters\User;
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
    public function getName()
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
     * @param User $user The user to render the profile link for, or null to render the link for the current user.
     *
     * @return string The rendered profile link.
     */
    public function renderProfileLink(User $user = null)
    {
        if (is_null($user)) {
            $user = $this->guard->user();
        }

        return view('user.profile_link', compact('user'))->render();
    }
}
