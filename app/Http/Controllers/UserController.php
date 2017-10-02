<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers;

use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use MyBB\Core\Database\Repositories\{
    ProfileFieldGroupRepositoryInterface, UserProfileFieldRepositoryInterface, UserRepositoryInterface
};
use MyBB\Core\Exceptions\UserNotFoundException;

class UserController extends AbstractController
{
    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var UserProfileFieldRepositoryInterface
     */
    protected $userProfileFields;

    /**
     * @param UserRepositoryInterface $users
     * @param UserProfileFieldRepositoryInterface $userProfileFields
     */
    public function __construct(
        UserRepositoryInterface $users,
        UserProfileFieldRepositoryInterface $userProfileFields
    ) {
        $this->users = $users;
        $this->userProfileFields = $userProfileFields;
    }

    /**
     * @param int $id
     * @param string $slug
     * @param ProfileFieldGroupRepositoryInterface $profileFieldGroups
     * @param Breadcrumbs $breadcrumbs
     *
     * @return \Illuminate\View\View
     */
    public function profile(
        int $id,
        string $slug,
        ProfileFieldGroupRepositoryInterface $profileFieldGroups,
        Breadcrumbs $breadcrumbs
    ) {
        $user = $this->users->find($id);

        if (!$user) {
            throw new UserNotFoundException;
        }

        $groups = $profileFieldGroups->getAll();

        $breadcrumbs->setCurrentRoute('user.profile', $user);

        return view('user.profile', [
            'user'                 => $user,
            'profile_field_groups' => $groups,
        ]);
    }
}
