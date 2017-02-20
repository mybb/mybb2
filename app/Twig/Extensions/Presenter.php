<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Twig\Extensions;

use McCool\LaravelAutoPresenter\AutoPresenter;
use McCool\LaravelAutoPresenter\BasePresenter;

class Presenter extends \Twig_Extension
{
    /**
     * @var AutoPresenter
     */
    protected $decorator;

    /**
     * @param AutoPresenter $decorator
     */
    public function __construct(AutoPresenter $decorator)
    {
        $this->decorator = $decorator;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() : string
    {
        return 'MyBB_Twig_Extensions_Presenter';
    }

    /**
     * @return array
     */
    public function getFunctions() : array
    {
        return [
            new \Twig_SimpleFunction('present', [$this, 'present']),
        ];
    }

    /**
     * @param object $object
     *
     * @return BasePresenter
     */
    public function present($object)
    {
        return $this->decorator->decorate($object);
    }
}
