<?php

namespace MyBB\Core\Http\Controllers;

use Illuminate\Http\Request;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Models\ProfileFieldGroup;
use MyBB\Core\Database\Repositories\IUserRepository;
use MyBB\Core\Database\Repositories\ProfileFieldGroupRepositoryInterface;
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
     * @param ProfileFieldGroupRepositoryInterface $profileFieldGroups
     * @return \Illuminate\View\View
     */
    public function profile($slug, $id, ProfileFieldGroupRepositoryInterface $profileFieldGroups)
    {
        $user = $this->users->find($id);
        $aboutFields = $this->userProfileFields->findForProfileFieldGroup($user, $profileFieldGroups->getBySlug(ProfileFieldGroup::ABOUT_YOU));
        $contactFields = $this->userProfileFields->findForProfileFieldGroup($user, $profileFieldGroups->getBySlug(ProfileFieldGroup::CONTACT_DETAILS));

        return view('user.profile', [
            'user' => $user,
            'about_fields' => $aboutFields,
            'contact_fields' => $contactFields
        ]);
    }
}
