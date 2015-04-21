<?php namespace MyBB\Core\Http\Controllers;

class AdminController extends AbstractController
{

	/*
	|--------------------------------------------------------------------------
	| Admin Base Controller
	|--------------------------------------------------------------------------
	|
	| Base Controller for Admin Pages
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
		// Eventually Something will go here
	}

	public function index()
	{
		return "<h1>Welcome to the Admin CP";
	}
}
