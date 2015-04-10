<?php namespace MyBB\Core\Http\Controllers;

use Illuminate\Auth\Guard;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\Filesystem;


class CaptchaController extends Controller
{
	private $database;
	private $files;

	private $gd_version = null;

	private $img_width = 200;
	private $img_height = 60;

	// Used for TTF fonts
	private $min_size = 20;
	private $max_size = 32;
	private $min_angle = -30;
	private $max_angle = 30;

	public function __construct(DatabaseManager $database, Filesystem $files)
	{
		$this->database = $database;
		$this->files = $files;
	}

	public function captcha($imagehash)
	{
		$baseQuery = $this->database->table('captcha')
			->where('imagehash', '=', $imagehash)
			->where('used', '=', false);

		// Something is wrong here...
		if ($baseQuery->count() != 1) {
			exit;
		}

		$imagestring = $baseQuery->pluck('imagestring');

		// Mark as used
		$baseQuery->update([
			'used' => true
		]);

//		$imagestring = 'Debug';

		if ($this->getGdVersion() >= 2) {
			$im = imagecreatetruecolor($this->img_width, $this->img_height);
		} else {
			$im = imagecreate($this->img_width, $this->img_height);
		}

		// Couldn't create the image :(
		if ($im === false) {
			throw new \RuntimeException("Couldn't create an image using GD");
		}

		// Fill the background with white
		$bg_color = imagecolorallocate($im, 255, 255, 255);
		imagefill($im, 0, 0, $bg_color);

		// Now draw random circles, squares or lines
		$to_draw = mt_rand(0, 2);
		if ($to_draw == 1) {
			$this->drawCircles($im);
		} elseif ($to_draw == 2) {
			$this->drawSquares($im);
		} else {
			$this->drawLines($im);
		}

		// Dots are always added
		$this->drawDots($im);

		// Now write the image string to the image
		$this->drawString($im, $imagestring);

		// Draw a nice border around the image
		$border_color = imagecolorallocate($im, 0, 0, 0);
		imagerectangle($im, 0, 0, $this->img_width - 1, $this->img_height - 1, $border_color);

		// And now output the image
		header('Content-type: image/png');
		imagepng($im);
		imagedestroy($im);
	}

	private function drawLines(&$im)
	{
		for ($i = 10; $i < $this->img_width; $i += 10) {
			$color = imagecolorallocate($im, mt_rand(150, 255), mt_rand(150, 255), mt_rand(150, 255));
			imageline($im, $i, 0, $i, $this->img_height, $color);
		}
		for ($i = 10; $i < $this->img_height; $i += 10) {
			$color = imagecolorallocate($im, mt_rand(150, 255), mt_rand(150, 255), mt_rand(150, 255));
			imageline($im, 0, $i, $this->img_width, $i, $color);
		}
	}

	private function drawCircles(&$im)
	{
		$circles = $this->img_width * $this->img_height / 100;
		for ($i = 0; $i <= $circles; ++$i) {
			$color = imagecolorallocate($im, mt_rand(180, 255), mt_rand(180, 255), mt_rand(180, 255));
			$pos_x = mt_rand(1, $this->img_width);
			$pos_y = mt_rand(1, $this->img_height);
			$circ_width = ceil(mt_rand(1, $this->img_width) / 2);
			$circ_height = mt_rand(1, $this->img_height);
			imagearc($im, $pos_x, $pos_y, $circ_width, $circ_height, 0, mt_rand(200, 360), $color);
		}
	}

	private function drawSquares(&$im)
	{
		$square_count = 30;
		for ($i = 0; $i <= $square_count; ++$i) {
			$color = imagecolorallocate($im, mt_rand(150, 255), mt_rand(150, 255), mt_rand(150, 255));
			$pos_x = mt_rand(1, $this->img_width);
			$pos_y = mt_rand(1, $this->img_height);
			$sq_width = $sq_height = mt_rand(10, 20);
			$pos_x2 = $pos_x + $sq_height;
			$pos_y2 = $pos_y + $sq_width;
			imagefilledrectangle($im, $pos_x, $pos_y, $pos_x2, $pos_y2, $color);
		}
	}

	private function drawDots(&$im)
	{
		$dot_count = $this->img_width * $this->img_height / 5;
		for ($i = 0; $i <= $dot_count; ++$i) {
			$color = imagecolorallocate($im, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
			imagesetpixel($im, mt_rand(0, $this->img_width), mt_rand(0, $this->img_height), $color);
		}
	}

	private function drawString(&$im, $string)
	{
		// Check whether we have true-type fonts and if so use them
		$ttf_fonts = [];
		if (function_exists('imagefttext')) {
			$fonts = $this->files->files(base_path('resources/captcha_fonts'));
			foreach ($fonts as $font) {
				if ($this->files->extension($font) == 'ttf') {
					$ttf_fonts[] = $font;
				}
			}
		}

		$spacing = $this->img_width / mb_strlen($string);
		$string_length = mb_strlen($string);
		for ($i = 0; $i < $string_length; ++$i) {
			// Using TTF fonts
			if (!empty($ttf_fonts)) {
				// Select a random font size
				$font_size = mt_rand($this->min_size, $this->max_size);
				// Select a random font
				$font = array_rand($ttf_fonts);
				$font = $ttf_fonts[$font];
				// Select a random rotation
				$rotation = mt_rand($this->min_angle, $this->max_angle);
				// Set the colour
				$r = mt_rand(0, 200);
				$g = mt_rand(0, 200);
				$b = mt_rand(0, 200);
				$color = imagecolorallocate($im, $r, $g, $b);
				// Fetch the dimensions of the character being added
				$dimensions = imageftbbox($font_size, $rotation, $font, $string[$i], array());
				$string_width = $dimensions[2] - $dimensions[0];
				$string_height = $dimensions[3] - $dimensions[5];
				// Calculate character offsets
				//$pos_x = $pos_x + $string_width + ($string_width/4);
				$pos_x = $spacing / 4 + $i * $spacing;
				$pos_y = ceil(($this->img_height - $string_height / 2));
				// Draw a shadow
				$shadow_x = mt_rand(-3, 3) + $pos_x;
				$shadow_y = mt_rand(-3, 3) + $pos_y;
				$shadow_color = imagecolorallocate($im, $r + 20, $g + 20, $b + 20);
				imagefttext($im, $font_size, $rotation, $shadow_x, $shadow_y, $shadow_color, $font, $string[$i],
					array());
				// Write the character to the image
				imagefttext($im, $font_size, $rotation, $pos_x, $pos_y, $color, $font, $string[$i], array());
			} else {
				// Get width/height of the character
				$string_width = imagefontwidth(5);
				$string_height = imagefontheight(5);
				// Calculate character offsets
				$pos_x = $spacing / 4 + $i * $spacing;
				$pos_y = $this->img_height / 2 - $string_height - 10 + mt_rand(-3, 3);
				// Create a temporary image for this character
				if ($this->getGdVersion() >= 2) {
					$temp_im = imagecreatetruecolor(15, 20);
				} else {
					$temp_im = imagecreate(15, 20);
				}
				$bg_color = imagecolorallocate($temp_im, 255, 255, 255);
				imagefill($temp_im, 0, 0, $bg_color);
				imagecolortransparent($temp_im, $bg_color);
				// Set the colour
				$r = mt_rand(0, 200);
				$g = mt_rand(0, 200);
				$b = mt_rand(0, 200);
				$color = imagecolorallocate($temp_im, $r, $g, $b);
				// Draw a shadow
				$shadow_x = mt_rand(-1, 1);
				$shadow_y = mt_rand(-1, 1);
				$shadow_color = imagecolorallocate($temp_im, $r + 50, $g + 50, $b + 50);
				imagestring($temp_im, 5, 1 + $shadow_x, 1 + $shadow_y, $string[$i], $shadow_color);
				imagestring($temp_im, 5, 1, 1, $string[$i], $color);
				// Copy to main image
				imagecopyresized($im, $temp_im, $pos_x, $pos_y, 0, 0, 40, 55, 15, 20);
				imagedestroy($temp_im);
			}
		}
	}

	private function getGdVersion()
	{
		if ($this->gd_version != null) {
			return $this->gd_version;
		}

		if (!extension_loaded('gd')) {
			$this->gd_version = 0;

			return 0;
		}

		if (function_exists("gd_info")) {
			$gd_info = gd_info();
			preg_match('/\d/', $gd_info['GD Version'], $gd);
			$this->gd_version = $gd[0];
		} else {
			ob_start();
			phpinfo(8);
			$info = ob_get_contents();
			ob_end_clean();
			$info = stristr($info, 'gd version');
			preg_match('/\d/', $info, $gd);
			$this->gd_version = $gd[0];
		}

		return $this->gd_version;
	}
}
