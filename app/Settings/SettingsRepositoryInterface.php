<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Settings;

interface SettingsRepositoryInterface
{
    public function all();

    public function get($key, $default = null);

    public function set($key, $value);

    public function delete($keyLike);
}
