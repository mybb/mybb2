<?php

namespace MyBB\Core\Twig\Extensions;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Moderation\ArrayModerationInterface;
use MyBB\Core\Moderation\ModerationRegistry;
use MyBB\Core\Moderation\ReversibleModerationInterface;
use MyBB\Core\Permissions\PermissionChecker;
use MyBB\Core\Presenters\Moderations\ReversibleModerationPresenterInterface;

class Moderation extends \Twig_Extension
{
    /**
     * @var ModerationRegistry
     */
    protected $moderationRegistry;

    /**
     * @var PermissionChecker
     */
    protected $permissionChecker;

    /**
     * @param ModerationRegistry $moderationRegistry
     * @param PermissionChecker $permissionChecker
     */
    public function __construct(ModerationRegistry $moderationRegistry, PermissionChecker $permissionChecker)
    {
        $this->moderationRegistry = $moderationRegistry;
        $this->permissionChecker = $permissionChecker;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() : string
    {
        return 'MyBB_Twig_Extensions_Moderation';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('is_reversible_moderation', [$this, 'isReversibleModeration']),
            new \Twig_SimpleFunction('is_array_moderation', [$this, 'isArrayModeration']),
            new \Twig_SimpleFunction(
                'render_moderation_button',
                [$this, 'renderModerationButton'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @param object $moderation
     *
     * @return bool
     */
    public function isReversibleModeration($moderation) : bool
    {
        return $moderation instanceof ReversibleModerationInterface
        || $moderation instanceof ReversibleModerationPresenterInterface;
    }

    /**
     * @param object $moderation
     *
     * @return bool
     */
    public function isArrayModeration($moderation) : bool
    {
        if ($moderation instanceof BasePresenter) {
            return $moderation->getWrappedObject() instanceof ArrayModerationInterface;
        }

        return $moderation instanceof ArrayModerationInterface;
    }

    /**
     * @param string $moderationName
     *
     * @param string $contentName
     * @param int $contentId
     *
     * @return \Illuminate\View\View
     */
    public function renderModerationButton(string $moderationName, string $contentName, int $contentId)
    {
        $moderation = $this->moderationRegistry->get($moderationName);

        if ($moderation && $this->permissionChecker->hasPermission('user', null, $moderation->getPermissionName())) {
            return view('partials.moderation.moderation_button', [
                'moderation'   => $moderation,
                'content_name' => $contentName,
                'content_id'   => $contentId,
            ]);
        }
    }
}
