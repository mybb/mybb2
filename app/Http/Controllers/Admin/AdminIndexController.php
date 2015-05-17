<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers\Admin;

use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;

class AdminIndexController extends AdminController
{
	/**
	 * @var Breadcrumbs
	 */
	protected $breadcrumbs;

	/**
	 * @param Breadcrumbs $breadcrumbs
	 */
	public function __construct(Breadcrumbs $breadcrumbs)
	{
		$this->breadcrumbs = $breadcrumbs;
	}

	public function index()
	{
		$this->breadcrumbs->setCurrentRoute('admin.index');
		return view('admin.index');
	}
}
