<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers\Admin\Users;

use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Core\Database\Repositories\RoleRepositoryInterface;
use MyBB\Core\Http\Controllers\Admin\AdminController;
use MyBB\Core\Http\Requests\User\CreateRequest;
use MyBB\Core\Http\Requests\User\SaveUserRequest;

class UserController extends AdminController
{
    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;
    
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;
    
    /**
     * Create a new user controller instance.
     *
     * @param Breadcrumbs $breadcrumbs
     * @param UserRepositoryInterface $userRepository
     * @param RoleRepositoryInterface $roleRepository
     */
    public function __construct(
        Breadcrumbs $breadcrumbs,
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository
    ) {
        $this->breadcrumbs = $breadcrumbs;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }
    
    /**
     * Show the user list.
     *
     * @return \Illuminate\View\View
     */
    public function users(Request $request)
    {
        $username = $request->input('username', '');
        $email = $request->input('email', '');
        $role = $this->roleRepository->findIdBySlug($request->input('role'));
        if ($role==null) {
            $role = 0;
        }
        
        $this->breadcrumbs->setCurrentRoute('admin.users.list');
        $users = $this->userRepository->search($username, $email, $role);
        $roles = $this->roles();
        $roles["any"] = "Any";

        return view('admin.users.list', compact('users', 'roles', 'username', 'email'))->withActive("users");
    }
    
    /**
     * Show the user edit form.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function editUser(int $id)
    {
        $this->breadcrumbs->setCurrentRoute('admin.users.edit');
        $user = $this->userRepository->find($id);
        if (!$user) {
            return redirect()->back()->withError(trans('errors.user_not_found'));
        }
        $role = $user->displayRole();
        $roles = $this->roles();
        
        return view('admin.users.edit', compact('user', 'roles', 'role'))->withActive("users");
    }
    
    /**
     * Save user after a valid user edit request.
     *
     * @param SaveUserRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUser(SaveUserRequest $request, int $id)
    {
        $user = $this->userRepository->find($id);
        
        $values = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'usertitle' => $request->input('usertitle'),
        ];
        
        if ($request->has('password')) {
            $values['password'] = bcrypt($request->input('password'));
        }
        
        $user->update($values);
        $user->setUpdatedAt($user->freshTimestamp())->save();
        
        $role = $this->roleRepository->findIdBySlug($request->input('role'));
        $user->roles()->update(['role_id' => $role]);
        
        return redirect()->back()->withSuccess(trans('admin::general.success_saved'));
    }
    
    /**
     * Show the add user form.
     *
     * @return \Illuminate\View\View
     */
    public function addUser()
    {
        $this->breadcrumbs->setCurrentRoute('admin.users.add');
        $roles = $this->roles();
        
        return view('admin.users.add', compact('roles'))->withActive("users");
    }
    
    /**
     * Show a confirmation page to delete the selected user.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function delete(int $id)
    {
        $this->breadcrumbs->setCurrentRoute('admin.users.delete');
        $user = $this->userRepository->find($id);
        if (!$user) {
            return redirect()->back()->withError(trans('errors.user_not_found'));
        }
        
        return view('admin.users.delete', compact('user'))->withActive('users');
    }
    
    /**
     * Delete the selected user
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function deleteUser(Request $request)
    {
        $this->userRepository->delete($request->get('user_id'));

        return redirect()->back()->withSuccess(trans('admin::general.success_deleted'));
    }
    
    /**
     * Create a new user instance after a valid user creation request.
     *
     * @param CreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CreateRequest $request)
    {
        $user = $this->userRepository->create([
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'password'  => bcrypt($request->input('password')),
        ]);
        if ($user) {
            $user->update(['last_visit' => $user->freshTimestamp()]);
            $user->roles()->attach($this->roleRepository->findIdBySlug($request->input('role')), ['is_display' => 1]);
            
            return redirect()->route('admin.users')->withSuccess(trans('admin::users.user_create_success'));
        }
        
        return redirect()->back()->withInput()->withErrors([
            'error' => trans('admin::users.error_creating_user'),
        ]);
    }
    
    /**
     * Get all roles.
     *
     * @return array $roles_list
     */
    protected function roles() : array
    {
        $roles = $this->roleRepository->all();
        
        $roles_list = [];
        foreach ($roles as $role) {
            $roles_list[$role['role_slug']] = $role['role_display_name'];
        }
        
        return $roles_list;
    }
}
