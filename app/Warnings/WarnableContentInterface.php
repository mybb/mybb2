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
     * Get name of content type
     *
     * @return string
     */
    public function getContentType();

    /**
     * @param integer $contentId
     * @return array
     */
    public function getWarningContent($contentId);

    /**
     * @param string $content
     * @return string
     */
    public function getWarningPreviewView($content);
}
