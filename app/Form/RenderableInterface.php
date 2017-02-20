<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Form;

interface RenderableInterface
{
    /**
     * @return string
     */
    public function getType() : string;

    /**
     * @return array
     */
    public function getOptions() : array;

    /**
     * @return string
     */
    public function getDescription() : string;

    /**
     * @return string
     */
    public function getElementName() : string;

    /**
     * @return string
     */
    public function getLabel() : string;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return string
     */
    public function getValidationRules() : string;
}
