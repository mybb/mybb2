<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Permissions\Interfaces;

use Illuminate\Support\Collection;

interface PermissionInterface
{
    /**
     * @param mixed $key
     *
     * @return $this
     */
    public static function find($key);

    /**
     * @return Collection
     */
    public static function all();

    /**
     * @return string
     */
    public static function getViewablePermission();

    /**
     * @return int
     */
    public function getContentId();
}
