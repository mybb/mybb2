<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

use MyBB\Core\Database\Models\AccessToken;
use MyBB\Core\Kernel\AbstractServer as BaseAbstractServer;
use MyBB\Core\Kernel\Application;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Server;
use Zend\Stratigility\MiddlewareInterface;

abstract class AbstractServer extends BaseAbstractServer
{
    public function listen()
    {
        Server::createServer(
            $this,
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        )->listen();
    }

    /**
     * Use as PSR-7 middleware.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $out
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null)
    {
        $app = $this->getApp();

        $this->collectGarbage($app);

        $middleware = $this->getMiddleware($app);

        return $middleware($request, $response, $out);
    }

    /**
     * @param Application $app
     * @return MiddlewareInterface
     */
    abstract protected function getMiddleware(Application $app);

    private function collectGarbage()
    {
        if ($this->hitsLottery()) {
            //todo: Implement EmailToken, and PasswordToken models
            AccessToken::whereRaw('last_activity <= ? - lifetime', [time()])->delete();

            $earliestToKeep = date('Y-m-d H:i:s', time() - 24 * 60 * 60);
        }
    }

    private function hitsLottery()
    {
        return mt_rand(1, 100) <= 2;
    }
}