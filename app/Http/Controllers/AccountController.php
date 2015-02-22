<?php namespace MyBB\Core\Http\Controllers;

class AccountController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Account Base Controller
	|--------------------------------------------------------------------------
	|
	| Base Controller for Account Pages
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
		return view('account.dashboard');
	}

    public function getProfile()
    {
        return view('account.profile');
    }

    public function getNotifications()
    {
        return view('account.notifications');
    }

    public function getFollowing()
    {
        return view('account.following');
    }

    public function getBuddies()
    {
        return view('account.buddies');
    }

    public function getPreferences()
    {
        return view('account.preferences');
    }

    public function getPrivacy()
    {
        return view('account.privacy');
    }

    public function getDrafts()
    {
        return view('account.drafts');
    }
}
