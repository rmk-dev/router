<?php

/**
 * The router service contract
 */
namespace Rmk\Router;

use Rmk\Router\Adapter\RouterAdapterInterface;

/**
 * Interface RouterServiceInterface
 *
 * @package Rmk\Router
 */
interface RouterServiceInterface extends RouterInterface
{

    /**
     * Sets new router adapter
     *
     * @param RouterAdapterInterface $adapter The new router adapter
     *
     * @return mixed
     */
    public function setRouterAdapter(RouterAdapterInterface $adapter);

    /**
     * Returns the router adapter
     *
     * @return RouterAdapterInterface The router adapter
     */
    public function getRouterAdapter(): RouterAdapterInterface;

    /**
     * Loads routes from configuration
     *
     * @param array $config The configuration
     *
     * @return mixed Implementation-specific value
     */
    public function loadFromConfig(array $config);
}
