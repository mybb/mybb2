<?php

namespace MyBB\Core\Http\Controllers\Admin\Settings;

use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use Illuminate\Http\Request;
use MyBB\Core\Database\Models\ProfileFieldOption;
use MyBB\Core\Database\Repositories\ProfileFieldGroupRepositoryInterface;
use MyBB\Core\Database\Repositories\ProfileFieldRepositoryInterface;
use MyBB\Core\Http\Controllers\Admin\AdminController;

class ProfileFieldController extends AdminController
{
	/**
	 * @var Breadcrumbs
	 */
	private $breadcrumbs;

	/**
	 * @var ProfileFieldRepositoryInterface
	 */
	private $profileFieldRepository;

	/**
	 * @param Breadcrumbs $breadcrumbs
	 * @param ProfileFieldRepositoryInterface $profileFieldRepository
	 */
	public function __construct(Breadcrumbs $breadcrumbs, ProfileFieldRepositoryInterface $profileFieldRepository)
	{
		$this->breadcrumbs = $breadcrumbs;
		$this->profileFieldRepository = $profileFieldRepository;
	}

	/**
	 * @param ProfileFieldGroupRepositoryInterface $profileFieldGroupRepository
	 *
	 * @return \Illuminate\View\View
	 */
	public function profileFields(ProfileFieldGroupRepositoryInterface $profileFieldGroupRepository)
	{
		$this->breadcrumbs->setCurrentRoute('admin.settings.profile_fields');
		return view('admin.settings.profile_fields', [
			'profile_field_groups' => $profileFieldGroupRepository->getAll()
		])->withActive('settings');
	}

	public function addProfileField()
	{
		$this->breadcrumbs->setCurrentRoute('admin.settings.add_profile_field');
		return view('admin.settings.add_profile_field')->withActive('settings');
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveNewProfileField(Request $request)
	{
		$data = $request->except(['_token']);
		$this->profileFieldRepository->create($data);
		return redirect('/admin/settings/profile-fields');
	}

	/**
	 * @param int $id
	 *
	 * @return \Illuminate\View\View
	 */
	public function editProfileField($id)
	{
		$this->breadcrumbs->setCurrentRoute('admin.settings.edit_profile_field');
		$field = $this->profileFieldRepository->find($id);
		return view('admin.settings.edit_profile_field', ['field' => $field])->withActive('settings');
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveProfileField(Request $request, $id)
	{
		$field = $this->profileFieldRepository->find($id);
		$field->update($request->only(['type', 'name', 'description', 'validation_rules']));
		return redirect()->back()->withSuccess('Saved!');
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function deleteProfileField(Request $request)
	{
		$this->profileFieldRepository->delete($request->get('profile_field_id'));
		return redirect()->back()->withSuccess('Deleted!');
	}

	/**
	 * @param int $id
	 *
	 * @return \Illuminate\View\View
	 */
	public function editProfileFieldOptions($id)
	{
		$field = $this->profileFieldRepository->find($id);
		$options = ProfileFieldOption::getForProfileField($field);
		return view('admin.settings.edit_profile_field_options', [
			'options' => $options,
			'field' => $field
		])->withActive('settings');
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function deleteProfileFieldOption(Request $request)
	{
		$option = ProfileFieldOption::find($request->get('profile_field_option_id'));
		$option->delete();
		return redirect()->back()->withSuccess('Deleted!');
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveNewProfileFieldOption(Request $request)
	{
		$data = [
			'name' => $request->get('name'),
			'value' => $request->get('name'),
			'profile_field_id' => $request->get('profile_field_id')
		];

		ProfileFieldOption::create($data);

		return redirect()->back()->withSuccess('Created!');
	}
}
