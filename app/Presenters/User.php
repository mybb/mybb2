<?php
/**
 * User presenter class.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Request;
use Lang;
use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\User as UserModel;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Database\Repositories\PostRepositoryInterface;
use MyBB\Core\Database\Repositories\TopicRepositoryInterface;
use MyBB\Core\Database\Repositories\UserRepositoryInterface;

class User extends BasePresenter
{
	/** @var UserModel $wrappedObject */

	/** @var Router $router */
	private $router;
	/** @var ForumRepositoryInterface $forumRepository */
	private $forumRepository;
	/** @var TopicRepositoryInterface $topicRepository */
	private $topicRepository;
	/** @var PostRepositoryInterface $postRepository */
	private $postRepository;
	/** @var UserRepositoryInterface $userRepository */
	private $userRepository;


	/**
	 * @param UserModel $resource The user being wrapped by this presenter.
	 * @param Router $router
	 * @param ForumRepositoryInterface $forumRepository
	 * @param PostRepositoryInterface $postRepository
	 * @param TopicRepositoryInterface $topicRepository
	 * @param UserRepositoryInterface $userRepository
	 */
	public function __construct(
		UserModel $resource,
		Router $router,
		ForumRepositoryInterface $forumRepository,
		PostRepositoryInterface $postRepository,
		TopicRepositoryInterface $topicRepository,
		UserRepositoryInterface $userRepository
	) {
		$this->wrappedObject = $resource;
		$this->router = $router;
		$this->forumRepository = $forumRepository;
		$this->topicRepository = $topicRepository;
		$this->postRepository = $postRepository;
		$this->userRepository = $userRepository;
	}

	public function styled_name()
	{
		if ($this->wrappedObject->id == -1) {
			return e(trans('general.guest'));
		}

		if ($this->wrappedObject->role != null && $this->wrappedObject->role->role_username_style) {
			return str_replace(':user', e($this->wrappedObject->name), $this->wrappedObject->role->role_username_style);
		}

		return e($this->wrappedObject->name);
	}

	public function avatar()
	{
		$avatar = $this->wrappedObject->avatar;

		// Empty? Default avatar
		if (empty($avatar)) {
			return asset('images/avatar.png');
		} // Link? Nice!
		elseif (filter_var($avatar, FILTER_VALIDATE_URL) !== false) {
			return $avatar;
		} // Email? Set up Gravatar
		elseif (filter_var($avatar, FILTER_VALIDATE_EMAIL) !== false) {
			// TODO: Replace with euans package
			return "http://gravatar.com/avatar/" . md5(strtolower(trim($avatar)));
		} // File?
		elseif (file_exists(public_path("uploads/avatars/{$avatar}"))) {
			return asset("uploads/avatars/{$avatar}");
		} // Nothing?
		else {
			return asset('images/avatar.png');
		}
	}

	public function avatar_link()
	{
		$avatar = $this->wrappedObject->avatar;

		// If we have an email or link we'll return it - otherwise nothing
		if (filter_var($avatar, FILTER_VALIDATE_URL) !== false || filter_var($avatar, FILTER_VALIDATE_EMAIL) !== false)
		{
			return $avatar;
		}

		return '';
	}

	public function last_page()
	{
		$lang = null;

		$collection = $this->router->getRoutes();
		$route = $collection->match(Request::create($this->wrappedObject->last_page));

		if ($route->getName() != null && Lang::has('online.' . $route->getName())) {
			$langOptions = $this->getWioData($route->getName(), $route->parameters());

			if (!isset($langOptions['url'])) {
				$langOptions['url'] = route($route->getName(), $route->parameters());
			}

			$lang = Lang::get('online.' . $route->getName(), $langOptions);

			// May happen if we have two routes 'xy.yx.zz' and 'xy.yx'
			if (is_array($lang)) {
				$lang = Lang::get('online.' . $route->getName() . '.index', $langOptions);
			}
		}

		if ($lang == null) {
//			$lang = Lang::get('online.unknown', ['url' => '']);
			// Used for debugging, should be left here until we have added all routes
			$lang = 'online.' . $route->getName();
		}

		return $lang;
	}

	private function getWioData($route, $parameters)
	{
		$data = array();

		switch ($route) {
			case 'forums.show':
				$data['forum'] = e($this->forumRepository->find($parameters['id'])->title);
				break;
			case 'topics.show':
			case 'topics.reply':
			case 'topics.quote':
			case 'topics.reply.post':
			case 'topics.edit':
			case 'topics.delete':
			case 'topics.restore':
				$data['topic'] = e($this->topicRepository->find($parameters['id'])->title);
				$data['url'] = route('topics.show', [$parameters['slug'], $parameters['id']]);
				break;
			case 'topics.create':
				$forum = $this->forumRepository->find($parameters['forumId']);
				$data['forum'] = e($forum->title);
				$data['url'] = route('forums.show', [$forum->slug, $forum->id]);
				break;
			case 'search.post':
			case 'search.results':
				$data['url'] = route('search');
				break;
		}

		// TODO: Here's a nice place for a plugin hook

		return $data;
	}
}
