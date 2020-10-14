<?php

/**
 * The router contract
 */
namespace Rmk\Router;

use Psr\Http\Message\RequestInterface;

/**
 * Interface RouterInterface
 *
 * @package Rmk\Router
 */
interface RouterInterface
{

    /**
     * Match a URL and optionally a method against a route and return it if exists
     *
     * @param string|null $url The URL to match against
     * @param string|null $method [Optional] A HTTP method
     *
     * @return Route|null The matched route or null if no route found
     *
     * @todo Return NotFoundRoute if no route is found€
     */
    public function matchUrl(?string $url = null, ?string $method = null): Route;

    /**
     * Match a request agains a route and return it if exists
     *
     * @param RequestInterface|null $request The request
     *
     * @return Route The matched route. Null if not found
     *
     * @todo Return NotFoundRoute if no route is found
     */
    public function match(RequestInterface $request): Route;

    /**
     * Compiles a URL by route name and parameters
     *
     * @param string $name      The route name
     * @param array $params     The route parameters
     * @param array|null $query The URL query parameters (?param1=value1&...paramN=valueN)
     * @param bool $full        Whether to use the full URL or relational
     *
     * @return string The compiled URL
     */
    public function url(string $name, array $params = [], ?array $query = null, bool $full = false): string;
}
