<?php

namespace MyBB\Core\Http\Controllers;

use Illuminate\Http\Request;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Repositories\IUserRepository;
use MyBB\Core\Database\Repositories\UserProfileFieldRepositoryInterface;

class UserController extends Controller
{
    /**
     * @var IUserRepository
     */
    protected $users;

    /**
     * @var UserProfileFieldRepositoryInterface
     */
    protected $userProfileFields;

    /**
     * @param Guard $guard
     * @param Request $request
     * @param IUserRepository $users
     * @param UserProfileFieldRepositoryInterface $userProfileFields
     */
    public function __construct(
        Guard $guard,
        Request $request,
        IUserRepository $users,
        UserProfileFieldRepositoryInterface $userProfileFields
    ) {
        parent::__construct($guard, $request);

        $this->users = $users;
        $this->userProfileFields = $userProfileFields;
    }

    /**
     * @param string $slug
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function profile($slug, $id)
    {
        $user = $this->users->find($id);
        $userProfileFields = $this->userProfileFields->findForUser($user);
        return view('user.profile', ['user' => $user, 'user_profile_fields' => $userProfileFields]);
    }
}
