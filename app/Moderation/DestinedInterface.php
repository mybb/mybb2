<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Moderation;

interface DestinedInterface
{
    /**
     * @return string
     */
    public function getDestinationType();

    /**
     * @return string
     */
    public function getDestinationKey();
}
