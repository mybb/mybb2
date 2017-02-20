<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers;

use Illuminate\Auth\Access\Response;
use MyBB\Core\Database\Repositories\WarningsRepositoryInterface;
use MyBB\Core\Database\Repositories\WarningTypesRepositoryInterface;
use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Core\Exceptions\UserNotFoundException;
use MyBB\Core\Exceptions\UserNotBelongsToThisContentException;
use MyBB\Core\Exceptions\WarningTypeNotFoundException;
use MyBB\Core\Exceptions\WarningNotFoundException;
use MyBB\Core\Warnings\WarningsManager;
use MyBB\Core\Http\Requests\Warnings\WarnUserRequest;
use MyBB\Core\Http\Requests\Warnings\RevokeWarnRequest;
use MyBB\Core\Http\Requests\Warnings\AckWithWarnRequest;
use MyBB\Core\Presenters\UserPresenter;
use MyBB\Settings\Store;
use Illuminate\Contracts\Auth\Guard;
use Carbon\Carbon;

class WarningsController extends AbstractController
{

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var WarningsRepositoryInterface
     */
    protected $warnings;

    /**
     * @var WarningTypesRepositoryInterface
     */
    protected $warningTypesRepository;

    /**
     * @var WarningsManager
     */
    protected $WarningsManager;

    /**
     * @var Guard
     */
    protected $guard;

    /**
     * @var Store
     */
    protected $settings;

    /**
     * WarningsController constructor.
     * @param UserRepositoryInterface $users
     * @param WarningsRepositoryInterface $warnings
     * @param WarningTypesRepositoryInterface $warningTypes
     * @param WarningsManager $WarningsManager
     * @param Guard $guard
     * @param Store $settings
     */
    public function __construct(
        UserRepositoryInterface $users,
        WarningsRepositoryInterface $warnings,
        WarningTypesRepositoryInterface $warningTypes,
        WarningsManager $WarningsManager,
        Guard $guard,
        Store $settings
    ) {
        $this->users = $users;
        $this->warnings = $warnings;
        $this->warningTypesRepository = $warningTypes;
        $this->WarningsManager = $WarningsManager;
        $this->guard = $guard;
        $this->settings = $settings;
    }

    /**
     * @param int $userId
     * @param $contentType
     * @param int $contentId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function warnUser(int $userId, $contentType, int $contentId)
    {
        $user = $this->users->find($userId);
        if (!$user) {
            throw new UserNotFoundException;
        }

        //todo check if user is warnable

        $warningContent = $this->WarningsManager->getWarningContentClass($contentType);
        $content = $warningContent->getWarningContent($contentId);
        if ($user->id != $content['user_id']) {
            throw new UserNotBelongsToThisContentException($user->name);
        }

        $previewContent = $warningContent->getWarningPreviewView($content['content']);

        $warningTypes = $this->warningTypesRepository->all();

        if ($this->settings->get('warnings.allow_zero')) {
            $minPoints = 0;
        } else {
            $minPoints = 1;
        }

        return view('warnings.create', compact(
            'user',
            'contentType',
            'contentId',
            'warningTypes',
            'previewContent',
            'minPoints'
        ));
    }

    /**
     * @param int $userId
     * @param $contentType
     * @param int $contentId
     * @param WarnUserRequest $request
     * @return mixed
     */
    public function createWarnUser(int $userId, $contentType, int $contentId, WarnUserRequest $request)
    {
        $dataToSave = [];
        $user = $this->users->find($userId);
        $inputs = $request->all();

        $warningContent = $this->WarningsManager->getWarningContentClass($contentType);
        $content = $warningContent->getWarningContent($contentId);
        if ($user->id != $content['user_id']) {
            throw new UserNotBelongsToThisContentException($user->name);
        }

        $dataToSave['user_id'] = $user->id;
        if ($contentType !== null & $contentId !== null) {
            $dataToSave['content_type'] = $contentType;
            $dataToSave['content_id'] = (int)$contentId;
        }

        if ($content['content']) {
            $dataToSave['snapshot'] = $content['content'];
        }

        $warningType = $inputs['warningType'];
        if ($warningType === 'custom') {
            if (!$this->settings->get('warnings.allow_custom')) {
                return abort(404);
            }

            $dataToSave['reason'] = $inputs['custom_reason'];
            $dataToSave['points'] = (int)$inputs['custom_points'];
            $dataToSave['must_acknowledge'] = (int)$inputs['must_acknowledge']['custom'];

            if (!$inputs['custom_expires_at']) {
                $dataToSave['expires_at'] = null;
            } else {
                $dataToSave['expires_at'] = Carbon::parse($inputs['custom_expires_at'])->timestamp;
            }
        } else {
            // grab warn from warning types
            $warn = $this->warningTypesRepository->find((int)$warningType);
            if (!$warn) {
                throw new WarningTypeNotFoundException();
            }

            $dataToSave['reason'] = $warn->reason;
            $dataToSave['points'] = $warn->points;

            if ($warn->must_acknowledge == 2) {
                $dataToSave['must_acknowledge'] = (int)$inputs['must_acknowledge'][$warn->id];
            } else {
                $dataToSave['must_acknowledge'] = $warn->must_acknowledge;
            }

            if ($warn->expiration_type == 'never') {
                $dataToSave['expires_at'] = null;
            } else {
                $timeToAddMethod = 'add' . ucfirst($warn->expiration_type) . 's';
                $dataToSave['expires_at'] = Carbon::now()->{$timeToAddMethod}($warn->expiration_multiple)->timestamp;
            }
        }

        // Are you trying add more points to this user than max points value is?
        $final_points = $user->warn_points + $dataToSave['points'];
        if ($final_points > $this->settings->get('warnings.max_points')) {
            $updateUser['warn_points'] = $this->settings->get('warnings.max_points');
        } else {
            $updateUser['warn_points'] = $final_points;
        }

        if (!$user->warned && $dataToSave['must_acknowledge']) {
            $updateUser['warned'] = 1;
        }

        $this->warnings->create($dataToSave);
        // todo add warning levels
        $this->users->update($user, $updateUser);
        $userPresenter = app()->make(UserPresenter::class, [$user]);

        return redirect()->route('user.profile', [
            'id'   => $user->id,
            'slug' => $user->name,
        ])->withSuccess(trans('warnings.saved_warn', [
            'user'          => $user->name,
            'warning_level' => $userPresenter->getWarningPercent,
        ]));
    }

    /**
     * @param int $id
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showWarnsForUser(int $id, $slug)
    {
        $warns = $this->warnings->findForUser($id);

        return view('warnings.show_for_user', compact('warns'));
    }

    /**
     * @param int $warnId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function warnDetails(int $warnId)
    {
        $warn = $this->warnings->find($warnId);

        if (!$warn) {
            throw new WarningNotFoundException;
        }

        $warningContent = $this->WarningsManager->getWarningContentClass($warn->content_type);
        $snapshot = $warningContent->getWarningPreviewView($warn->snapshot);

        return view('warnings.show', compact('warn', 'snapshot'));
    }

    /**
     * @param RevokeWarnRequest $request
     * @return Response
     */
    public function revokeWarn(RevokeWarnRequest $request)
    {
        $warn = $this->warnings->find($request->input('id'));
        if (!$warn || $warn->expired || $warn->revoked_at) {
            throw new WarningNotFoundException;
        }

        $this->warnings->revoke($warn, $request->input('reason'));
        $this->recountUserAck($warn->owner);

        return redirect()->route('warnings.show', [
            'warnId' => $warn->id,
        ])->withSuccess(trans('warnings.warn_revoked'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function acknowledgeWithWarn()
    {
        if (!$this->guard->user()->warned) {
            return abort(404);
        }

        $warn = $this->warnings->lastAckWarn($this->guard->user()->id);
        if (!$warn) {
            //there is no warnings with acknowledge flag - fix user
            $this->guard->user()->update(['warned' => 0]);
            return redirect()->route('forum.index');
        }

        return view('warnings.acknowledge', compact('warn'));
    }

    /**
     * @param AckWithWarnRequest $request
     * @return mixed
     */
    public function postAcknowledgeWithWarn(AckWithWarnRequest $request)
    {
        $warn = $this->warnings->find($request->input('id'));
        $this->warnings->update($warn, ['must_acknowledge' => 0]);
        $this->recountUserAck($this->guard->user());

        return redirect()->route('forum.index')->withSuccess(trans('warnings.ack.success'));
    }

    /**
     * Update acknowledge user flag
     *
     * @param $user
     * @return \MyBB\Core\Database\Models\User
     */
    private function recountUserAck($user)
    {
        if ($this->warnings->ackWarnCount($user->id) > 0) {
            $update['warned'] = 1;
        } else {
            $update['warned'] = 0;
        }

        return $this->users->update($user, $update);
    }
}
