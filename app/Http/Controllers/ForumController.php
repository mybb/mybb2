<?php namespace MyBB\Core\Http\Controllers;

class ForumController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Forum Controller
	|--------------------------------------------------------------------------
	| Renders the index page, 
	| 
	| 
	| @TODO - subforums, Posts
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

	/**
	 * Shows the Index Page
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('index.index');
	}

}
