<?php

/**
 * The route class
 */
declare(strict_types=1);

namespace Rmk\Router;

/**
 * Class Route
 *
 * @package Rmk\Router
 */
class Route
{

    /**
     * The route name
     *
     * @var string
     */
    protected $name;

    /**
     * The route URL
     *
     * @var string
     */
    protected $url;

    /**
     * The HTTP method
     *
     * @var string
     */
    protected $method;

    /**
     * The request handler
     *
     * @var mixed
     */
    protected $handler;

    /**
     * The route params
     *
     * @var array
     */
    protected $params;

    /**
     * Route constructor.
     *
     * @param string|null $name
     * @param string|null $url
     * @param string|null $method
     * @param mixed|null  $handler
     * @param array       $params
     */
    public function __construct(
        ?string $name = null,
        ?string $url = null,
        ?string $method = null,
        $handler = null,
        array $params = []
    ) {
        $this->setName($name);
        $this->setUrl($url);
        $this->setMethod($method);
        $this->setHandler($handler);
        $this->setParams($params);
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): Route
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string|null $method
     * @return $this
     */
    public function setMethod(?string $method): Route
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param $handler
     * @return $this
     */
    public function setHandler($handler): Route
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): Route
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param string|null $url
     * @return $this
     */
    public function setUrl(?string $url): Route
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $param
     * @return bool
     */
    public function hasParam(string $param): bool
    {
        return array_key_exists($param, $this->params);
    }

    /**
     * @param string $paramName
     * @return mixed|null
     */
    public function getParam(string $paramName)
    {
        return $this->hasParam($paramName) ? $this->params[$paramName] : null;
    }
}
