<?php namespace MyBB\Core\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use MyBB\Auth\Contracts\Guard;
use View;

abstract class Controller extends BaseController
{

	use DispatchesCommands, ValidatesRequests
	{
		ValidatesRequests::getRedirectUrl as parentGetRedirectUrl;
	}

	protected $failedValidationRedirect = '';

	public function __construct(Guard $guard)
	{
		View::share('auth_user', $guard->user());

		if($guard->check())
		{
			$guard->user()->update([
				                       'last_visit' => new \DateTime()
			                       ]);
		}
	}

	protected function getRedirectUrl()
	{
		if(!empty($this->failedValidationRedirect))
		{
			return $this->failedValidationRedirect;
		}

		return $this->parentGetRedirectUrl();
	}
}
