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
	 * @var ProfileFieldGroupRepositoryInterface
	 */
	private $profileFieldGroupRepository;

	/**
	 * @param Breadcrumbs $breadcrumbs
	 * @param ProfileFieldRepositoryInterface $profileFieldRepository
	 * @param ProfileFieldGroupRepositoryInterface $profileFieldGroupRepository
	 */
	public function __construct(
		Breadcrumbs $breadcrumbs,
		ProfileFieldRepositoryInterface $profileFieldRepository,
		ProfileFieldGroupRepositoryInterface $profileFieldGroupRepository
	) {
		$this->breadcrumbs = $breadcrumbs;
		$this->profileFieldRepository = $profileFieldRepository;
		$this->profileFieldGroupRepository = $profileFieldGroupRepository;
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function profileFields()
	{
		$this->breadcrumbs->setCurrentRoute('admin.settings.profile_fields');
		return view('admin.settings.profile_fields', [
			'profile_field_groups' => $this->profileFieldGroupRepository->getAll()
		])->withActive('settings');
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function addProfileField()
	{
		$this->breadcrumbs->setCurrentRoute('admin.settings.profile_fields.add');
		$groups = $this->profileFieldGroupRepository->getAllForSelectElement();
		return view('admin.settings.profile_fields.add', ['groups' => $groups])->withActive('settings');
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
		return redirect('/admin/settings/profile-fields')->withSuccess('Saved!');
	}

	/**
	 * @param int $id
	 *
	 * @return \Illuminate\View\View
	 */
	public function editProfileField($id)
	{
		$this->breadcrumbs->setCurrentRoute('admin.settings.profile_fields.edit');
		$field = $this->profileFieldRepository->find($id);
		$groups = $this->profileFieldGroupRepository->getAllForSelectElement();
		return view('admin.settings.profile_fields.edit', ['field' => $field, 'groups' => $groups])->withActive('settings');
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
		return view('admin.settings.profile_fields.edit_options', [
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
