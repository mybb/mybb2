<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Permissions\Interfaces;

interface InheritPermissionInterface extends PermissionInterface
{
    /**
     * Returns an array of permissions where a positive permission in one of the parents overrides negative permissions
     * in its child
     *
     * @return array
     */
    public static function getPositiveParentOverrides();

    /**
     * Returns an array of permissions where a negative permission in one of the parents overrides positive permissions
     * in its child
     *
     * @return array
     */
    public static function getNegativeParentOverrides();

    /**
     * @return InheritPermissionInterface|null
     */
    public function getParent();
}
