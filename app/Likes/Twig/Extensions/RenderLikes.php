<?php
/**
 * Twig helper to render likes for a piece of content.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Likes\Twig\Extensions;

use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Translation\Translator;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Likes\Database\Models\Like;
use MyBB\Settings\Store;
use Twig_SimpleFunction;

class RenderLikes extends \Twig_Extension
{
    /**
     * @var Store $settings
     */
    private $settings;
    /**
     * @var Translator $lang
     */
    private $lang;
    /**
     * @var Factory $viewFactory
     */
    private $viewFactory;
    /**
     * @var Guard $guard
     */
    private $guard;

    /**
     * @param Store      $settings
     * @param Translator $lang
     * @param Factory    $viewFactory
     * @param Guard      $guard
     */
    public function __construct(Store $settings, Translator $lang, Factory $viewFactory, Guard $guard)
    {
        $this->settings = $settings;
        $this->lang = $lang;
        $this->viewFactory = $viewFactory;
        $this->guard = $guard;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'MyBB_Core_Likes_Twig_Extensions_RenderLikes';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('render_likes', [$this, 'render'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Render the likes into a string.
     *
     * @param Collection $likesCollection The likes to render.
     *
     * @param string           $viewAllLikesLink The link to view all of the likes for the content.
     *
     * @return string
     */
    public function render(Collection $likesCollection, $viewAllLikesLink)
    {
        $numLikesToList = $this->settings->get('posts.likes_to_show', 3);
        $numOtherLikes = $likesCollection->count() - $numLikesToList;

        $userId = $this->guard->user()->getAuthIdentifier();

        $likes = [];

        $likesCollection = $likesCollection->filter(function(Like $like) use (&$likes, &$numLikesToList, $userId) {
            if ($like->user->id === $userId) {
                $like->user->name = $this->lang->get('likes.current_user');
                $likes[] = $like;
                $numLikesToList--;
                return false;
            }

            return true;
        });

        $numLikesInCollection = $likesCollection->count();

        if ($numLikesInCollection > 0 && $numLikesToList > 0) {
            if ($numLikesInCollection < $numLikesToList) {
                $numLikesToList = $numLikesInCollection;
            }

            $randomLikes = $likesCollection->random($numLikesToList);

            if (!is_array($randomLikes)) { // random returns a single model if $numLikesToList is 1...
                $randomLikes = array($randomLikes);
            }

            foreach ($randomLikes as $key => $like) {
                $likes[] = $like;
            }
        }

        return $this->viewFactory->make('likes.list', compact('numOtherLikes', 'likes', 'viewAllLikesLink'))->render();
    }
}
