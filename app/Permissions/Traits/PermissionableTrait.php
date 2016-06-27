<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Permissions\Traits;

use MyBB\Core\Services\PermissionChecker;

trait PermissionableTrait
{
    /**
     * @return string
     */
    public static function getViewablePermission()
    {
        return 'canView' . ucfirst(class_basename(__CLASS__));
    }

    /**
     * @return int
     */
    public function getContentId()
    {
        return $this->getKey();
    }
}
