<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Captcha;

interface CaptchaInterface
{
    /**
     * @return string
     */
    public function render();

    /**
     * @return bool
     */
    public function validate();

    /**
     * @return bool
     */
    public function supported();
}
