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
use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Core\Widgets\WidgetInterface;
use MyBB\Settings\Store;

class UsersOnlineWidget implements WidgetInterface
{
    /**
     * @var \MyBB\Core\Database\Repositories\UserRepositoryInterface $userRepository
     */
    protected $userRepository;

    /**
     * @var Store $settings
     */
    protected $settings;

    /**
     * @var Factory $viewFactory
     */
    protected $viewFactory;

    /**
     * Initialise the users online widget.
     *
     * @param UserRepositoryInterface $userRepository
     * @param Store $settings
     * @param Factory $viewFactory
     */
    public function __construct(UserRepositoryInterface $userRepository, Store $settings, Factory $viewFactory)
    {
        $this->userRepository = $userRepository;
        $this->settings = $settings;
        $this->viewFactory = $viewFactory;
    }

    /**
     * Get the name of the widget.
     *
     * @return string The name of the widget.
     */
    public static function getName(): string
    {
        return 'users_online';
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
        $users = $this->userRepository->online($this->settings->get('wio.minutes', 15), 'name', 'asc', 0);
        return $this->viewFactory->make('widgets.users_online', compact('users'));
    }
}
