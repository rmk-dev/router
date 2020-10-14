<?php

/**
 * The router service class
 */
namespace Rmk\Router;

use Psr\Http\Message\RequestInterface;
use Rmk\Router\Adapter\RouterAdapterInterface;
use Rmk\Router\Exception\RouterConfigException;

/**
 * Class RouterService
 *
 * @package Rmk\Router
 */
class RouterService implements RouterServiceInterface
{

    /**
     * The router adapter
     *
     * @var RouterAdapterInterface
     */
    protected $adapter;

    /**
     * RouterService constructor.
     *
     * @param RouterAdapterInterface $adapter The chosen router adapter
     */
    public function __construct(RouterAdapterInterface $adapter)
    {
        $this->setRouterAdapter($adapter);
    }

    /**
     * {@inheritDoc}
     */
    public function setRouterAdapter(RouterAdapterInterface $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getRouterAdapter(): RouterAdapterInterface
    {
        return $this->adapter;
    }

    /**
     * {@inheritDoc}
     */
    public function loadFromConfig(array $config): void
    {
        foreach ($config as $name => $cfg) {
            $this->adapter->add($this->createRoute($name, $cfg));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function createRoute(string $name, array $config): Route
    {
        if (!array_key_exists('url', $config)) {
            throw new RouterConfigException('URL key is missing', 1);
        } else {
            $url = $config['url'];
        }
        $method = $config['method'] ?? null;
        if (!array_key_exists('handler', $config)) {
            throw new RouterConfigException('Handler key is missing', 2);
        } else {
            $handler = $config['handler'];
        }
        $params = $config['params'] ?? [];
        $middlewares = $config['middlewares'] ?? [];
        return new Route($name, $url, $method, $handler, $params, $middlewares);
    }

    /**
     * Add a route to the adapter
     *
     * @param Route $route The route to be added
     */
    public function add(Route $route): void
    {
        $this->adapter->add($route);
    }

    /**
     * {@inheritDoc}
     */
    public function matchUrl(?string $url = null, ?string $method = null): Route
    {
        return $this->adapter->matchUrl($url, $method);
    }

    /**
     * {@inheritDoc}
     */
    public function match(RequestInterface $request): Route
    {
        return $this->adapter->match($request);
    }

    /**
     * {@inheritDoc}
     */
    public function url(string $name, array $params = [], ?array $query = null, bool $full = false): string
    {
        return $this->adapter->url($name, $params, $query, $full);
    }
}
