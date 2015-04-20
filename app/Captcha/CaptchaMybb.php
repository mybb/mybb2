<?php

namespace MyBB\Core\Captcha;

use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Request;

class CaptchaMybb implements CaptchaInterface
{

	private $database;
	private $request;

	public function __construct(DatabaseManager $database, Request $request)
	{
		$this->database = $database;
		$this->request = $request;
	}

	public function render()
	{
		$imagehash = md5(str_random(12));

		$this->database->table('captcha')->insert([
			'imagehash' => $imagehash,
			'imagestring' => str_random(5),
			'created_at' => new \DateTime()
		]);

		return view('captcha.mybb', compact('imagehash'));
	}

	public function validate()
	{
		$check = $this->database->table('captcha')
			->where('imagehash', '=', $this->request->get('imagehash'))
			->where('imagestring', '=', $this->request->get('imagestring'));

		if ($check->count() != 1) {
			$this->database->table('captcha')
				->where('imagehash', '=', $this->request->get('imagehash'))
				->delete();

			return false;
		}

		return true;
	}

	public function supported()
	{
		// We need to be able to create images
		return function_exists('imagecreatefrompng');
	}
}
