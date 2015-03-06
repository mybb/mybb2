<?php

namespace MyBB\Core\Http\Controllers;

use Illuminate\Support\Facades\Input;
use MyBB\Parser\MessageFormatter;
use MyBB\Parser\ParserFactory;

class DebugController extends Controller
{
	public function parser()
	{
		$orig = trim(Input::get("text"));
		$type = Input::get("parser");
		$parsed = "";

		if(!empty($type) && !empty($orig))
		{
			$parser = ParserFactory::make($type);
			$parser->setPostURL(":pid");

//			$formatter = new MessageFormatter($parser);
			$app = app();
			$formatter = $app->make('MyBB\Parser\MessageFormatter', [$parser]);
			$parsed = $formatter->parse($orig, [
				MessageFormatter::ALLOW_HTML => false,
				MessageFormatter::ENABLE_MYCODE => true,
				MessageFormatter::ME_USERNAME => "Jones",
				MessageFormatter::HIGHLIGHT => ["Jon", "Tes"],
			]);
		}

		return view('debug.parser', ["orig" => $orig, "parsed" => $parsed]);
	}
}
