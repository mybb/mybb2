<?php

namespace MyBB\Core\Permissions\Interfaces;

interface InheritPermissionInterface extends PermissionInterface
{
    /**
     * @return InheritPermissionInterface|null
     */
    function getParent();

    /**
     * Returns an array of permissions where a positive permission in one of the parents overrides negative permissions
     * in its child
     *
     * @return array
     */
     static function getPositiveParentOverrides();

    /**
     * Returns an array of permissions where a negative permission in one of the parents overrides positive permissions
     * in its child
     *
     * @return array
     */
    static function getNegativeParentOverrides();
}