<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use MyBB\Core\Database\Repositories\{
    ForumRepositoryInterface, RoleRepositoryInterface
};
use MyBB\Core\Exceptions\SettingNotFoundException;
use MyBB\Core\Http\Controllers\Admin\AdminController;
use MyBB\Settings\Repositories\SettingRepositoryInterface;

class SettingsController extends AdminController
{
    /**
     * @var SettingRepositoryInterface
     */
    protected $settingRepository;

    /**
     * @var RoleRepositoryInterface
     */
    protected $roleRepository;

    /**
     * @var ForumRepositoryInterface
     */
    protected $forumRepository;

    /**
     * SettingsController constructor.
     * @param SettingRepositoryInterface $settingRepository
     * @param RoleRepositoryInterface $roleRepository
     * @param ForumRepositoryInterface $forumRepository
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        RoleRepositoryInterface $roleRepository,
        ForumRepositoryInterface $forumRepository
    ) {
        $this->settingRepository = $settingRepository;
        $this->roleRepository = $roleRepository;
        $this->forumRepository = $forumRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listGroups()
    {
        $groups = $this->settingRepository->getSettingsGroups(['user'])->unique();

        $core = [];
        $extensions = [];
        foreach ($groups as $group) {
            if ($group['package']['original'] == 'mybb/core') {
                $core[] = $group;
            } else {
                $extensions[] = $group;
            }
        }

        return view('admin.settings.groups', compact('core', 'extensions'))->withActive('settings');
    }

    /**
     * @param string $group
     * @param string $package
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editGroupOfSettings(string $group, string $package = 'mybb.core')
    {
        // get original package name
        $originalPackage = str_replace('.', '/', $package);

        $settings = $this->settingRepository->getSettingsForGroup($group, $originalPackage);
        if (!count($settings) || $group == 'user') {
            throw new SettingNotFoundException();
        }

        $roles = $this->roleRepository->all();
        $forums = $this->forumRepository->onlyChildren();

        $packageName = explode('.', $package);
        $packageName = $packageName[1];
        if ($packageName == 'core') {
            $packageName = 'admin';
        }

        return view('admin.settings.edit', compact('settings', 'group', 'package', 'roles', 'forums', 'packageName'))
            ->withActive('settings');
    }

    /**
     * @param Request $request
     * @param string $group
     * @param string $package
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function saveGroupOfSettings(Request $request, string $group, string $package = 'mybb.core')
    {
        $inputs = $request->get('setting');
        // restore original package name
        $package = str_replace('.', '/', $package);

        $oldSettings = $this->settingRepository->getSettingsForGroup($group, $package)->keyBy('name');

        $modifiedSettings = [];
        foreach ($oldSettings as $name => $setting) {
            switch ($setting['setting_type']) {
                case 'number':
                    $value = (int)$inputs[$name];
                    break;
                case 'checkbox':
                    $value = isset($inputs[$name]);
                    break;
                case 'switch':
                    $value = (bool)$inputs[$name];
                    break;
                case 'choose':
                case 'radio':
                case 'string':
                    $value = (string)$inputs[$name];
                    break;
                case 'multichoose':
                    $value = implode("|", $inputs[$name]);
                    break;
                case 'roleselect':
                case 'forumselect':
                    if ($inputs[$name] == "-1") {
                        // none
                        $value = null;
                    } elseif ($inputs[$name] == 0) {
                        // all
                        $value = 0;
                    } else {
                        // select
                        if (isset($inputs[$name . '_select'])) {
                            $value = implode("|", $inputs[$name . '_select']);
                        } else {
                            $value = null;
                        }
                    }
                    break;
                default:
                    $value = $inputs[$name];
                    break;
            }
            $modifiedSettings[$setting['name']] = $value;
        }

        $this->settingRepository->updateSettings($modifiedSettings, false, $package);

        return redirect()->route('admin.settings')->withSuccess(trans('admin::general.success_saved'));
    }
}
