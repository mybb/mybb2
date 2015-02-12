<?php namespace MyBB\Core\Http\Controllers\Admin;

use MyBB\Core\Http\Controllers\AdminController;

class AdminIndexController extends AdminController{

	/*
	|--------------------------------------------------------------------------
	| Admin Index Controller
	|--------------------------------------------------------------------------
	|
	| Handler for the AdminCp Dashboard
	|
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{

	}

	/**
	 * Shows the Index Page
	 *
	 * @return Response
	 */
	public function index()
	{
		return "<h1>Welcome to the AdminCp</h1>";
	}

}