<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response;

abstract class AbstractHtmlController implements ControllerInterface
{
    /**
     * @param Request $request
     * @return \Zend\Diactoros\Response
     */
    public function handle(Request $request)
    {
        $view = $this->render($request);

        $response = new Response;
        $response->getBody()->write($view);

        return $response;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    abstract protected function render(Request $request);
}