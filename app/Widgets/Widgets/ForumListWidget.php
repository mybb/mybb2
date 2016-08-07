<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Widgets\Widgets;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Widgets\WidgetInterface;

class ForumListWidget implements WidgetInterface
{
    /**
     * @var ForumRepositoryInterface $forumRepository;
     */
    protected $forumRepository;

    /**
     * @var Factory $viewFactory
     */
    protected $viewFactory;

    /**
     * Initialise the forum list widget.
     *
     * @param ForumRepositoryInterface $forumRepository
     */
    public function __construct(ForumRepositoryInterface $forumRepository, Factory $viewFactory)
    {
        $this->forumRepository = $forumRepository;
        $this->viewFactory = $viewFactory;
    }

    /**
     * Get the name of the widget.
     *
     * @return string The name of the widget.
     */
    public static function getName(): string
    {
        return 'forum_list';
    }

    /**
     * Render the widget.
     *
     * @param array $parameters An optional array of parameters passed to the widget.
     *
     * @return Renderable The renderable content to render within the view.
     */
    public function render(array $parameters = null): Renderable
    {
        $forums = $this->forumRepository->getIndexTree();
        return $this->viewFactory->make('widgets.forum_list', compact('forums'));
    }
}
