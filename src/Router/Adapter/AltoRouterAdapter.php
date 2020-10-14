<?php

/**
 * Class to adapt the AltoRouter object to adapter contract
 */
namespace Rmk\Router\Adapter;

use Rmk\Router\NotFoundRoute;
use Rmk\Router\Route;
use Psr\Http\Message\RequestInterface;
use \AltoRouter;

/**
 * Class AltoRouterAdapter
 *
 * @package Rmk\Router\Adapter
 */
class AltoRouterAdapter implements RouterAdapterInterface
{

    public const DEFAULT_METHODS = 'GET|POST|PUT|DELETE|OPTIONS|HEAD|PATCH';

    /**
     * @var AltoRouter
     */
    protected $altoRouter;

    /**
     * AltoRouterAdapter constructor.
     *
     * @param AltoRouter $altoRouter
     */
    public function __construct(AltoRouter $altoRouter)
    {
        $this->altoRouter = $altoRouter;
    }

    /**
     * {@inheritDoc}
     */
    public function add(Route $route)
    {
        $method = $route->getMethod();
        if (!$method) {
            $method = self::DEFAULT_METHODS;
        }
        $this->altoRouter->map($method, $route->getUrl(), $route, $route->getName());
    }

    /**
     * {@inheritDoc}
     */
    public function matchUrl(?string $url = null, ?string $method = null): Route
    {
        $matched = $this->altoRouter->match($url, $method);
        if ($matched) {
            $route = $matched['target'];
            $route->setParams($matched['params']);
            return $route;
        }

        return new NotFoundRoute(null, $url, $method);
    }

    /**
     * {@inheritDoc}
     */
    public function match(RequestInterface $request): Route
    {
        return $this->matchUrl($request->getUri().'', $request->getMethod());
    }

    /**
     * {@inheritDoc}
     */
    public function url(string $name, array $params = [], ?array $query = null, bool $full = false): string
    {
        $url = $this->altoRouter->generate($name, $params).'';
        if ($query) {
            $url .= '?'.http_build_query($query);
        }
        return $url;
        // TODO: implement full url
    }
}
