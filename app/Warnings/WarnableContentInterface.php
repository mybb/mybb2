<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Warnings;

interface WarnableContentInterface
{

    /**
     * @param integer $contentId
     * @return array
     */
    public function getWarningContent(int $contentId) : array;

    /**
     * @param string|null $content
     * @return string
     */
    public function getWarningPreviewView(string $content = null) : string;
}
