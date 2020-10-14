<?php

/**
 * Router adapter contract
 */
namespace Rmk\Router\Adapter;

use Rmk\Router\RouterInterface;
use Rmk\Router\Route;

/**
 * Interface RouterAdapterInterface
 *
 * @package Rmk\Router\Adapter
 */
interface RouterAdapterInterface extends RouterInterface
{

    /**
     * Add a route to the adapter stack
     *
     * @param Route $route A route object to add
     *
     * @return mixed Implementation-specific value
     */
    public function add(Route $route);
}