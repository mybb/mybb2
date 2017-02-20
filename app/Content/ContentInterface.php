<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Content;

interface ContentInterface
{
    /**
     * @return int
     */
    public function getId() : int;

    /**
     * @return string
     */
    public function getType() : string;

    /**
     * @return string
     */
    public function getUrl() : string;

    /**
     * @return string
     */
    public function getTitle() : string;
}
