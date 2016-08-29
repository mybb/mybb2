<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers\Admin\Users;

use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Core\Database\Repositories\RoleRepositoryInterface;
use MyBB\Core\Http\Controllers\Admin\AdminController;
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
     * @var Factory $validator
     */
    private $validator;

    /**
     * @var Request $request
     */
    private $request;
    
    /**
     * Create a new user controller instance.
     *
     * @param Breadcrumbs $breadcrumbs
     * @param Factory $validator
     * @param Request $request
     * @param UserRepositoryInterface $userRepository
     * @param RoleRepositoryInterface $roleRepository
     */
    public function __construct(
        Breadcrumbs $breadcrumbs,
        Factory $validator,
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository
    ) {
        $this->breadcrumbs = $breadcrumbs;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }
    
    /**
     * Show the user list.
     *
     * @return \Illuminate\View\View
     */
    public function users()
    {
        $this->breadcrumbs->setCurrentRoute('admin.users.list');
        $users = $this->userRepository->all();

        return view('admin.users.list', compact('users'))->withActive("users");
    }
    
    /**
     * Show the user edit form.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function editUser($id)
    {
        $this->breadcrumbs->setCurrentRoute('admin.users.edit');
        $user = $this->userRepository->find($id);
        $role = $user->displayRole();
        $roles = $this->roles();
        
        return view('admin.users.edit', compact('user', 'roles', 'role'))->withActive("users");
    }
    
    /**
     * Handle a user edit request.
     *
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUser(Request $request, $id)
    {
        $user = $this->userRepository->find($id);
        
        $validator = $this->validator->make($request->all(), [
            'name'      => 'required|max:255|unique:users,name,'.$user->id,
            'email'     => 'required|email|max:255|unique:users,email,'.$user->id,
            'password'  => 'confirmed|min:6',
            'usertitle' => 'string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        if ($request->has('password')) {
            $request['password'] = bcrypt($request['password']);
        }
        
        $user->update($request->only('name', 'email', 'password', 'usertitle'));
        $user->update(['updated_at' => $user->freshTimestamp()]);
        
        $role = $this->roleRepository->findIdBySlug($request['role']);
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
     * Handle a user creation request.
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveNewUser(Request $request)
    {
        $validator = $this->validator->make($request->all(), [
            'name'      => 'required|max:255|unique:users',
            'email'     => 'required|max:255|unique:users',
            'password'  => 'required|confirmed|min:6',
            'usertitle' => 'string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $this->create($request->all());
        
        return redirect()->route('admin.users')->withSuccess(trans('admin::general.success_created'));
    }
    
    /**
     * Create a new user instance after a valid user creation request.
     *
     * @param array $data
     */
    protected function create(array $data)
    {
        $user = $this->userRepository->create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => bcrypt($data['password']),
            'usertitle' => $data['usertitle'],
        ]);
        $user->update(['last_visit' => $user->freshTimestamp()]);
        $user->roles()->attach($this->roleRepository->findIdBySlug($data['role']), ['is_display' => 1]);
    }
    
    /**
     * Get all roles.
     *
     * @return array $roles_list
     */
    protected function roles()
    {
        $roles = $this->roleRepository->all();
        
        $roles_list = [];
        foreach ($roles as $role) {
            $roles_list[$role['role_slug']] = $role['role_display_name'];
        }
        
        return $roles_list;
    }
}
