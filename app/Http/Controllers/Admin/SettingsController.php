<?php

namespace MyBB\Core\Http\Controllers\Admin;

use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;

class SettingsController extends AdminController
{
	private $breadcrumbs;

	public function __construct(Breadcrumbs $breadcrumbs)
	{
		$this->breadcrumbs = $breadcrumbs;
	}

	public function index()
	{
		$this->breadcrumbs->setCurrentRoute('admin.settings');
		return view('admin.settings.index', ['active_tab' => 'settings']);
	}
}
