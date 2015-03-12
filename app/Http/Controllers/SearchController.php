<?php namespace MyBB\Core\Http\Controllers;

use Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Auth\Guard;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Forum;
use MyBB\Core\Database\Models\Search;
use MyBB\Core\Database\Repositories\IForumRepository;
use MyBB\Core\Database\Repositories\ISearchRepository;
use MyBB\Core\Http\Requests\Search\SearchRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class SearchController extends Controller
{
	/**
	 * @var ISearchRepository $searchRepository
	 * @access protected
	 */
	protected $searchRepository;

	/** @var IForumRepository $forumRepository */
	private $forumRepository;


	/**
	 * @var SearchRequest $searchRequest
	 * @access protected
	 */
	protected $searchRequest;

	/**
	 * Create a new controller instance.
	 *
	 * @param Guard $guard
	 * @param ISearchRepository $searchRepository
	 * @param IForumRepository $forumRepository
	 * @param Request $request
	 */
	public function __construct(
		Guard $guard,
		ISearchRepository $searchRepository,
		IForumRepository $forumRepository,
		Request $request
	)
	{
		parent::__construct($guard, $request);

		$this->searchRepository = $searchRepository;
		$this->forumRepository = $forumRepository;
	}
	
	public 	$sorts = [
		'postdate' => [
			'name' => 'created_at',
			'desc' => 'asc',
			'asc' => 'desc'
		],
		'author' => [
			'name' => 'user_id',
			'desc' => 'desc',
			'asc' => 'asc'
		],
		'subject' => [
			'name' => 'topics.title',
			'asc' => 'asc',
			'desc' => 'desc'
		],
		'forum' => [
			'name' => 'tppics.forum_id',
			'asc' => 'asc',
			'desc' => 'desc'
		],
		'replies' => [
			'name' => 'topics.num_posts',
			'asc' => 'desc',
			'desc' => 'asc'
		]
	];

	public function index()
	{
		$forumsList = $this->forumRepository->getIndexTree();

		$forums = [];

		$makeLists = function(&$forums, &$list, $tab = '') use (&$makeLists)
		{
			foreach($list as $forum)
			{
				$forums[$forum->id] = $tab.$forum->title;
				if(!empty($forum->children))
				{
					$makeLists($forums, $forum->children, $tab.'&nbsp;&nbsp;&nbsp;&nbsp;');
				}
			}
		};

		$makeLists($forums, $forumsList);


		return view('search.index', compact('forums'));
	}

	public function makeSearch(SearchRequest $searchRequest)
	{
		if($searchRequest->result != 'posts')
		{
			$searchRequest->result = 'topics';
		}

		if($searchRequest->result == 'topics')
		{
			$query = Topic::with(['firstPost']);
			$query->leftJoin('posts', 'topics.first_post_id', '=', 'posts.id');
			$query->where(function($query) use(&$searchRequest)
			{
				$query->where(function($query) use(&$searchRequest)
				{
					$query->where('topics.title', 'like', '%'.$searchRequest->keyword.'%');
					$query->orWhere('posts.content', 'like', '%'.$searchRequest->keyword.'%');
				});
			});
		}
		else
		{
			$query = Post::with(['topic']);
			$query->leftJoin('topics', 'posts.topic_id', '=', 'topics.id');
			$query->where('posts.content', 'like', '%'.$searchRequest->keyword.'%');
		}

		if($searchRequest->author)
		{
			$query->leftJoin('users', 'posts.user_id', '=', 'users.id');
			$query->where(function($query) use(&$searchRequest)
			{
				if($searchRequest->matchusername)
				{
					$query->where('users.name', $searchRequest->author);
					$query->orWhere('posts.username', $searchRequest->author);
				}
				else
				{
					$query->where('users.name', 'like', '%'.$searchRequest->author.'%');
					$query->orWhere('posts.username', 'like', '%'.$searchRequest->author.'%');
				}
			});
		}

		if($searchRequest->topic_replies_type)
		{
			switch ($searchRequest->topic_replies_type)
			{
				case 'atmost':
					$query->where('topics.num_posts', '<=', $searchRequest->topic_replies);
					break;
				case 'atleast':
					$query->where('topics.num_posts', '>=', $searchRequest->topic_replies);
					break;
				case 'exactly':
				default:
					$query->where('topics.num_posts', $searchRequest->topic_replies);
					break;
			}
		}

		if($searchRequest->post_date)
		{
			$postDateType = '>=';
			if ($searchRequest->post_date_type == 'older')
			{
				$postDateType = '<=';
			}
			switch ($searchRequest->post_date)
			{
				case 'yesterday':
					$postDate = '-1 day';
					break;
				case 'oneweek':
					$postDate = '-1 week';
					break;
				case 'twoweek':
					$postDate = '-2 weeks';
					break;
				case 'onemonth':
					$postDate = '-1 month';
					break;
				case 'threemonth':
					$postDate = '-3 months';
					break;
				case 'sixmonth':
					$postDate = '-3 months';
					break;
				case 'oneyear':
					$postDate = '-1 year';
					break;
				default:
					$postDate = '';
					break;
			}
			if ($postDate) {
				$query->where($searchRequest->result.'.created_at', $postDateType, new \DateTime('today ' . $postDate));
			}
		}

		if(is_array($searchRequest->forums) && (!empty($searchRequest->forums) || !in_array('-1', $searchRequest->forums)))
		{
			$query->whereIn('topics.forum_id', $searchRequest->forums);
		}
		
		if(!$searchRequest->sortby)
		{
			$searchRequest->sortby = 'postdate';
			$searchRequest->sorttype = 'asc';
		}
		if(!$searchRequest->sorttype)
		{
			$searchRequest->sorttype = 'asc';
		}

		$topics = [];
		$posts = [];
		$results = $query->get();

		if($searchRequest->result == 'topics')
		{
			foreach($results as $result)
			{
				$topics[] = $result->id;
				$posts[] = $result->firstPost->id;
			}
		}
		else
		{
			foreach($results as $result)
			{
				$topics[] = $result->topic->id;
				$posts[] = $result->id;
			}
		}
		$searchlog = $this->searchRepository->create([
			'topics' => implode(',', $topics),
			'posts' => implode(',', $posts),
			'keywords' => $searchRequest->keyword,
			'as_topics' => ($searchRequest->result == 'topics')
		]);
		return redirect()->route('search.results', [
			'id' => $searchlog->id,
			'orderBy' => $searchRequest->sortby,
			'orderDir' => $searchRequest->sorttype
		]);
	}

	public function results(Request $request, $id = 0)
	{
		// TODO: sorts
		$search = $this->searchRepository->find($id);
		if(!$search)
		{
			throw new NotFoundHttpException();
		}


		Breadcrumbs::setCurrentRoute('search.results', $search);

		$orderBy = $request->get('orderBy');
		$orderDir = $request->get('orderDir');

		if(!isset($this->sorts[$orderBy]))
		{
			$orderBy = 'postdate';
		}
		if($orderDir != 'asc')
		{
			$orderDir = 'desc';
		}
		$urlDirs = [];
		foreach($this->sorts as $sortName => $sort)
		{
			$urlDirs[$sortName] = $sort['asc'];
		}
		if($orderDir == $urlDirs[$orderBy])
		{
			if($urlDirs[$orderBy] == 'desc')
			{
				$urlDirs[$orderBy] = 'asc';
			}
			else
			{
				$urlDirs[$orderBy] = 'desc';
			}
		}

		if($search->as_topics)
		{
			$results = Topic::whereIn('id', explode(',', $search->topics))->with(['lastPost', 'author', 'lastPost.author'])->orderBy($this->sorts[$orderBy]['name'], $this->sorts[$orderBy][$orderDir])->paginate(10);
		}
		else
		{
			$results = Post::whereIn('id', explode(',', $search->posts))->with(['topic', 'author'])->orderBy($this->sorts[$orderBy]['name'], $this->sorts[$orderBy][$orderDir])->paginate(10);
		}


		if($search->as_topics)
		{
			return view('search.result_topics', compact('results', 'search', 'orderDir', 'orderBy', 'urlDirs'));
		}
		else
		{
			return view('search.result_posts', compact('results', 'search'));
		}
	}
}
