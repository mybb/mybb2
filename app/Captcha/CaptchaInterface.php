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
    public function render() : string;

    /**
     * @return bool
     */
    public function validate() : bool;

    /**
     * @return bool
     */
    public function supported() : bool;
}
