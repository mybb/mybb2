<?php namespace MyBB\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Guard;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Forum;
use MyBB\Core\Database\Models\Search;
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

	/**
	 * Create a new controller instance.
	 *
	 * @param Guard $guard
	 * @param ISearchRepository $searchRepository
	 */
	public function __construct(
		Guard $guard,
		ISearchRepository $searchRepository,
		Request $request
	)
	{
		parent::__construct($guard, $request);

		$this->searchRepository = $searchRepository;
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
			'name' => 'title',
			'asc' => 'asc',
			'desc' => 'desc'
		],
		'forum' => [
			'name' => 'forum_id',
			'asc' => 'asc',
			'desc' => 'desc'
		]
	];

	public function index()
	{
		return view('search.index');
	}

	public function makeSearch(SearchRequest $searchRequest)
	{
		$query = Topic::where('title', 'like', '%'.$searchRequest->keyword.'%');
		if($searchRequest->author)
		{
			// TODO
		}

		if($searchRequest->topic_replies_type)
		{
			switch ($searchRequest->topic_replies_type)
			{
				case 'atmost':
					$query->where('num_posts', '<=', $searchRequest->topic_replies);
					break;
				case 'atleast':
					$query->where('num_posts', '>=', $searchRequest->topic_replies);
					break;
				case 'exactly':
				default:
					$query->where('num_posts', $searchRequest->topic_replies);
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
				$query->where('created_at', $postDateType, new \DateTime('today ' . $postDate));
			}
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
		foreach($results as $result)
		{
			$topics[] = $result->id;
			$posts[] = $result->firstPost->id;
		}
		$searchlog = $this->searchRepository->create([
			'topics' => implode(',', $topics),
			'posts' => implode(',', $posts),
			'as_topic' => true // TODO: show results as posts
		]);
		return redirect()->route('search.results', [
			'id' => $searchlog->id,
			'orderBy' => $this->sorts[$searchRequest->sortby]['name'],
			'orderDir' => $this->sorts[$searchRequest->sortby][$searchRequest->sorttype]
		]);
	}

	public function results(Request $request, $id = 0)
	{
		// TODO: show results as topics/posts
		// TODO: sorts
		$search = $this->searchRepository->find($id);
		if(!$search)
		{
			throw new NotFoundHttpException();
		}

		$sortBy = $request->get('orderBy');
		$sortDir = $request->get('orderDir');

		if(!isset($this->sorts[$sortBy]))
		{
			$sortBy = 'postdate';
		}
		if($sortDir != 'asc')
		{
			$sortDir = 'asc';
		}


		$results = Topic::whereIn('id', explode(',', $search->topics))->with(['lastPost', 'author'])->orderBy($this->sorts[$sortBy]['name'], $this->sorts[$sortBy][$sortDir])->paginate(10);
		return view('search.result', compact('results'));
	}
}
