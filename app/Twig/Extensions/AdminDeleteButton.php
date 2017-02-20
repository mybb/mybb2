<?php
/**
 * List all forums extension for Twig.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Twig\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;

class AdminDeleteButton extends Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'MyBB_Twig_Extensions_AdminDeleteButton';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('admin_delete_button', [$this, 'deleteButton'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param $for
     * @param $id
     * @return string
     */
    public function deleteButton($for, $id) : string
    {
        return view('admin.partials.delete_button', compact('for', 'id'))->render();
    }
}
