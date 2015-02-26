<?php

namespace MyBB\Core\Services;

use MyBB\Core\Database\Models\User;
use DB;
use Lang;
use URL;
use Mail;

class ConfirmationManager
{

	public static function send($type, User $user, $route, $newData = null, $langData = array(), $delete = true)
	{
		// Generate the token - always needed
		$token = str_random();

		// Do we need to delete old confirmations for this user/type combination?
		if($delete)
		{
			DB::table('confirmations')->where('type', $type)->where('user_id', $user->id)->delete();
		}

		// Now insert our data set
		DB::table('confirmations')->insert([
			'token' => $token,
			'type' => $type,
			'user_id' => $user->id,
			'newData' => $newData,
		]);

		// Now build the subject/message
		$link = URL::route($route, $token);
		$langData['link'] = $link;

		if(Lang::has("confirmation.{$type}_subject"))
		{
			$subject = Lang::get("confirmation.{$type}_subject", $langData);
		}
		else
		{
			$subject = Lang::get('confirmation.subject', array_merge($langData, ['type' => $type]));
		}

		if(Lang::has("confirmation.{$type}_message"))
		{
			$message = Lang::get("confirmation.{$type}_message", $langData);
		}
		else
		{
			$message = Lang::get('confirmation.message', array_merge($langData, ['type' => $type]));
		}

		// Finally send it
		Mail::raw($message, function($mail) use ($user, $subject)
		{
			// TODO: board email
			$mail->from('admin@mybb.com');
			$mail->to($user->email);
			$mail->subject($subject);
		});
	}

	public static function get($type, $token, $delete = true)
	{
		$baseQuery = DB::table('confirmations')->where('type', $type)->where('token', $token);

		// Something is wrong - either we don't have a valid token here or the same token multiple times
		if($baseQuery->count() != 1)
		{
			return false;
		}

		$newData = $baseQuery->pluck('newData');

		if($delete)
		{
			$baseQuery->delete();
		}

		return $newData;
	}

	public static function has($type, User $user)
	{
		$count = DB::table('confirmations')->where('type', $type)->where('user_id', $user->id)->count();
		return $count > 0;
	}
}