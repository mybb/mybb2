<?php
/**
 * User presenter class.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Request;
use Illuminate\Translation\Translator;
use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\User as UserModel;
use MyBB\Core\Database\Repositories\ConversationRepositoryInterface;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Database\Repositories\PostRepositoryInterface;
use MyBB\Core\Database\Repositories\TopicRepositoryInterface;
use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Core\Permissions\PermissionChecker;
use MyBB\Gravatar\Generator;
use MyBB\Settings\Models\Setting;
use MyBB\Settings\Models\SettingValue;
use MyBB\Settings\Store;

class UserPresenter extends BasePresenter
{
    /** @var UserModel $wrappedObject */

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ForumRepositoryInterface
     */
    private $forumRepository;

    /**
     * @var TopicRepositoryInterface
     */
    private $topicRepository;

    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var PermissionChecker
     */
    private $permissionChecker;

    /**
     * @var ConversationRepositoryInterface
     */
    private $conversationRepository;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var Generator
     */
    private $gravatarGenerator;

    /**
     * @var Store
     */
    private $settings;

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @param UserModel $resource
     * @param Router $router
     * @param ForumRepositoryInterface $forumRepository
     * @param PostRepositoryInterface $postRepository
     * @param TopicRepositoryInterface $topicRepository
     * @param UserRepositoryInterface $userRepository
     * @param PermissionChecker $permissionChecker
     * @param ConversationRepositoryInterface $conversationRepository
     * @param Translator $translator
     * @param Generator $gravatarGenerator
     * @param Store $settings
     * @param Guard $guard
     */
    public function __construct(
        UserModel $resource,
        Router $router,
        ForumRepositoryInterface $forumRepository,
        PostRepositoryInterface $postRepository,
        TopicRepositoryInterface $topicRepository,
        UserRepositoryInterface $userRepository,
        PermissionChecker $permissionChecker,
        ConversationRepositoryInterface $conversationRepository,
        Translator $translator,
        Generator $gravatarGenerator,
        Store $settings,
        Guard $guard
    ) {
        parent::__construct($resource);

        $this->router = $router;
        $this->forumRepository = $forumRepository;
        $this->topicRepository = $topicRepository;
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->permissionChecker = $permissionChecker;
        $this->conversationRepository = $conversationRepository;
        $this->translator = $translator;
        $this->gravatarGenerator = $gravatarGenerator;
        $this->settings = $settings;
        $this->guard = $guard;
    }

    /**
     * @return string
     */
    public function styled_name()
    {
        if (empty($this->wrappedObject->name)) {
            $this->wrappedObject->name = trans('general.guest');
        }

        if ($this->wrappedObject->displayRole() != null && $this->wrappedObject->displayRole()->role_username_style) {
            return str_replace(
                ':user',
                e($this->wrappedObject->name),
                $this->wrappedObject->displayRole()->role_username_style
            );
        }

        return e($this->wrappedObject->name);
    }

    /**
     * @return string
     */
    public function avatar()
    {
        $avatar = $this->wrappedObject->avatar;

        // Empty? Default avatar
        if (empty($avatar)) {
            return asset('assets/images/avatar.png');
        } // Link? Nice!
        elseif (filter_var($avatar, FILTER_VALIDATE_URL) !== false) {
            return $avatar;
        } // Email? Set up Gravatar
        elseif (filter_var($avatar, FILTER_VALIDATE_EMAIL) !== false) {
            return $this->gravatarGenerator->setDefault(asset('assets/images/avatar.png'))->getGravatar($avatar);
        } // File?
        elseif (file_exists(public_path("uploads/avatars/{$avatar}"))) {
            return asset("uploads/avatars/{$avatar}");
        } // Nothing?
        else {
            return asset('assets/images/avatar.png');
        }
    }

    /**
     * @return string
     */
    public function avatar_link()
    {
        $avatar = $this->wrappedObject->avatar;

        // If we have an email or link we'll return it - otherwise nothing
        if (filter_var($avatar, FILTER_VALIDATE_URL) !== false || filter_var($avatar, FILTER_VALIDATE_EMAIL) !== false
        ) {
            return $avatar;
        }

        return '';
    }

    /**
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->permissionChecker->hasPermission('user', null, $permission);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function unreadConversations()
    {
        $conversations = $this->conversationRepository->getUnreadForUser($this->wrappedObject);

        foreach ($conversations as $key => $conversation) {
            $conversations[$key] = app()->make('MyBB\Core\Presenters\ConversationPresenter', [$conversation]);
        }

        return $conversations;
    }

    /**
     * @return bool
     */
    public function isOnline()
    {
        $minutes = $this->settings->get('wio.minutes', 15);

        // This user was logging out at last
        if ($this->wrappedObject->last_page == 'auth/logout') {
            return false;
        }

        // This user isn't online
        if (new \DateTime($this->wrappedObject->last_visit) < new \DateTime("{$minutes} minutes ago")) {
            return false;
        }

        // The user is online, now permissions

        // We're either testing our own account or have permissions to view everyone
        if ($this->permissionChecker->hasPermission('user', null, 'canViewAllOnline')
            || $this->guard->user()->id == $this->wrappedObject->id
        ) {
            return true;
        }

        // Next we need to get the setting for this user

        // First get the id of our setting
        $settingId = Setting::where('name', 'user.showonline')->first()->id;

        // Now the value
        $settingValue = SettingValue::where('user_id', '=', $this->wrappedObject->id)
            ->where('setting_id', '=', $settingId)->first();

        // Either the value isn't set (good) or true (better), let's show this user as online
        if ($settingValue == null || $settingValue->value == true) {
            return true;
        }

        // Still here? Then the viewing user doesn't have the permissions and we show him as offline
        return false;
    }

    /**
     * @return string
     */
    public function last_page()
    {
        $lang = null;

        $collection = $this->router->getRoutes();
        $route = $collection->match(Request::create($this->wrappedObject->last_page));

        if ($route->getName() != null) {
            $langOptions = $this->getWioData($route->getName(), $route->parameters());

            if (!isset($langOptions['url'])) {
                $langOptions['url'] = route($route->getName(), $route->parameters());
            }

            if (!isset($langOptions['langString'])) {
                $langString = 'online.' . $route->getName();
                ;
            } else {
                $langString = 'online.' . $langOptions['langString'];
                unset($langOptions['langString']);
            }

            $lang = $this->translator->get($langString, $langOptions);

            // May happen if we have two routes 'xy.yx.zz' and 'xy.yx'
            if (is_array($lang)) {
                $lang = $this->translator->get($langString . '.index', $langOptions);
            }
        }

        if ($lang == null) {
//			$lang = Lang::get('online.unknown', ['url' => '']);
            // Used for debugging, should be left here until we have added all routes
            $lang = 'online.' . $route->getName();
        }

        return $lang;
    }

    /**
     * @param string $route
     * @param array $parameters
     *
     * @return array
     */
    private function getWioData($route, array $parameters)
    {
        $data = [];

        switch ($route) {
            case 'forums.show':
                $forum = $this->forumRepository->find($parameters['id']);
                // Either the forum has been deleted or this user doesn't have permission to view it
                if ($forum != null) {
                    $data['forum'] = e($forum->title);
                } else {
                    $data['langString'] = 'forums.invalid';
                }
                break;
            case 'topics.show':
            case 'topics.reply':
            case 'topics.quote':
            case 'topics.reply.post':
            case 'topics.edit':
            case 'topics.delete':
            case 'topics.restore':
                $topic = $this->topicRepository->find($parameters['id']);
                // Either the topic has been deleted or this user doesn't have permission to view it
                if ($topic != null) {
                    $data['topic'] = e($topic->title);
                    $data['url'] = route('topics.show', [$parameters['slug'], $parameters['id']]);
                } else {
                    $data['langString'] = 'topics.invalid';
                }
                break;
            case 'topics.create':
                $forum = $this->forumRepository->find($parameters['forumId']);
                // Either the forum has been deleted or this user doesn't have permission to view it
                if ($forum != null) {
                    $data['forum'] = e($forum->title);
                    $data['url'] = route('forums.show', [$forum->slug, $forum->id]);
                } else {
                    $data['langString'] = 'forums.invalid';
                }
                break;
            case 'search.post':
            case 'search.results':
                $data['url'] = route('search');
                break;
            case 'user.profile':
                $user = $this->userRepository->find($parameters['id']);
                if ($user != null) {
                    $data['user'] = e($user->name);
                    $data['url'] = route('user.profile', [$user->name, $user->id]);
                } else {
                    $data['langString'] = 'user.invalid';
                }
                break;
            case 'conversations.index':
            case 'conversations.compose':
            case 'conversations.read':
            case 'conversations.reply':
            case 'conversations.leave':
            case 'conversations.newParticipant':
                $data['langString'] = 'conversations';
                break;
        }

        // TODO: Here's a nice place for a plugin hook

        return $data;
    }

    public function created_at()
    {
        return $this->wrappedObject->created_at;
    }

    public function last_visit()
    {
        return $this->wrappedObject->last_visit;
    }
}
