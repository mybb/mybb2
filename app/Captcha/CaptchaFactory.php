<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Captcha;

use Illuminate\Contracts\Foundation\Application;
use MyBB\Core\Exceptions\CaptchaInvalidClassException;
use MyBB\Settings\Store;

class CaptchaFactory implements CaptchaInterface
{
	/**
	 * @var Store
	 */
	private $settings;

	/**
	 * @var Application
	 */
	private $app;

	const NONE = 'none';
	const MYBB = 'mybb';
	const RECAPTCHA = 'recaptcha';
	const NOCAPTCHA = 'nocaptcha';

	/**
	 * @param Store       $settings
	 * @param Application $app
	 */
	public function __construct(Store $settings, Application $app)
	{
		$this->settings = $settings;
		$this->app = $app;
	}

	/**
	 * {@inheritdoc}
	 */
	public function render($captcha = false)
	{
		$captcha = $this->getCaptchaClass($captcha);

		// Not supported
		if ($captcha === null) {
			return '';
		}

		return $captcha->render();
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate($captcha = false)
	{
		$captcha = $this->getCaptchaClass($captcha);

		// Not supported
		if ($captcha === null) {
			return true;
		}

		return $captcha->validate();
	}

	/**
	 * {@inheritdoc}
	 */
	public function supported()
	{
		return true;
	}

	/**
	 * @param string $captchaName
	 *
	 * @return CaptchaInterface|null
	 *
	 * @throws CaptchaInvalidClassException
	 */
	private function getCaptchaClass($captchaName)
	{
		if ($captchaName == false) {
			$captchaName = $this->settings->get('captcha.method', static::NONE);
		}

		if ($captchaName === static::NONE) {
			return null;
		}

		$captchaClass = 'MyBB\\Core\\Captcha\\Captcha' . ucfirst($captchaName);

		if (!class_exists($captchaClass)) {
			return null;
		}

		$captcha = $this->app->make($captchaClass);

		if (!$captcha || !($captcha instanceof CaptchaInterface) || !$captcha->supported()) {
			throw new CaptchaInvalidClassException($captchaClass);
		}

		return $captcha;
	}
}
