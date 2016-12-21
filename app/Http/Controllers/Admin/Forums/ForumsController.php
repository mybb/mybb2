<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers\Admin\Forums;

use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use MyBB\Core\Database\Repositories\{
    ForumRepositoryInterface, TopicRepositoryInterface
};
use MyBB\Core\Exceptions\ForumNotFoundException;
use MyBB\Core\Http\Controllers\Admin\AdminController;
use MyBB\Core\Http\Requests\Forums\CreateForumRequest;

class ForumsController extends AdminController
{

    /**
     * @var ForumRepositoryInterface
     */
    protected $forumRepository;

    /**
     * @var TopicRepositoryInterface
     */
    protected $topicRepository;

    /**
     * ForumsController constructor.
     * @param ForumRepositoryInterface $forumRepository
     * @param TopicRepositoryInterface $topicRepository
     */
    public function __construct(ForumRepositoryInterface $forumRepository, TopicRepositoryInterface $topicRepository)
    {
        $this->forumRepository = $forumRepository;
        $this->topicRepository = $topicRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $forums = $this->forumRepository->all()->toTree();
        return view('admin.forums.list', compact('forums'))->withActive('forums');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        $forums = $this->forumRepository->all()->toTree();
        return view('admin.forums.add', compact('forums'))->withActive('forums');
    }

    /**
     * @param CreateForumRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function create(CreateForumRequest $request)
    {
        $details = [
            'slug'        => str_slug($request->get('slug')),
            'title'       => $request->get('title'),
            'description' => $request->get('description'),
        ];

        if ((bool)$request->get('type')) {
            $details['parent_id'] = $request->get('parent');
        } else {
            $details['parent_id'] = null;
        }

        // set forum order
        if ($this->forumRepository->isEmpty()) {
            // There isn't any forums yet. Make this first
            $details['left_id'] = 1;
            $details['right_id'] = 2;
            if ((bool)$request->get('type')) {
                return back()->withInput()->withErrors(trans('admin::forums.error.create_category'));
            }
        } else {

            if ((bool)$request->get('order')) {
                $forumOrder = $this->forumRepository->getForum($request->get('order'));
            } else {
                $forumOrder = $this->forumRepository->getForum($request->get('parent'));
            }

            if ((bool)$request->get('order_position') && (bool)$request->get('order')) {
                // Set new forum after
                $details['left_id'] = $forumOrder->right_id + 1;
                $details['right_id'] = $forumOrder->right_id + 2;
            } elseif ((bool)$request->get('order')) {
                // Set new forum before
                $details['left_id'] = $forumOrder->left_id;
                $details['right_id'] = $forumOrder->left_id + 1;
            } else {
                // Save as first in parent
                $details['left_id'] = $forumOrder->left_id + 1;
                $details['right_id'] = $forumOrder->left_id + 2;
            }
        }

        // other options
        if ((bool)$request->get('link')) {
            $details['is_link'] = 1;
            $details['link'] = $request->get('link');
        } else {
            $details['is_link'] = 0;
        }

        if (!(bool)$request->get('open')) {
            $details['close'] = 1;
        }

        try {
            $this->forumRepository->create($details);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(trans('admin::general.error.try_again'));
        }

        return redirect()->route('admin.forums')->withSuccess(trans('admin::general.success_saved'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        dd($request->get('id'));
        $forum = $this->forumRepository->getForum($request->get('id'));
        if (!$forum) {
            throw new ForumNotFoundException;
        }

        try {
            $this->forumRepository->delete($forum);
        } catch (\Exception $e) {
            return back()->withErrors(trans('admin::general.error.try_again'));
        }

        return redirect()->route('admin.forums')->withSuccess(trans('admin::general.success_deleted'));
    }

    public function edit($id)
    {
        $forum = $this->forumRepository->getForum($id);
        return view('admin.forums.edit', compact('forum'))->withActive('forums');
    }

    public function update()
    {

    }
    
}
