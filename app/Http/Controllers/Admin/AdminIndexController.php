<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers\Admin;

class AdminIndexController extends AdminController
{
	public function index()
	{
		return 'Welcome to the MyBB ACP';
	}
}
