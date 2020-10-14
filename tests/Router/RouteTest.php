<?php

namespace Rmk\Tests\Router;

use PHPUnit\Framework\TestCase;
use Rmk\Router\Route;

class RouteTest extends TestCase
{
    
    public function testInitConstructor(): void
    {
        $handler = ['SomeController', 'someAction'];
        $params = ['a' => 1, 'b' => 2];
        $route = new Route('testRoute', '/test/url', 'GET', $handler, $params);
        $this->assertEquals('testRoute', $route->getName());
        $this->assertEquals('GET', $route->getMethod());
        $this->assertEquals($handler, $route->getHandler());
        $this->assertEquals($params, $route->getParams());
        $this->assertTrue($route->hasParam('a'));
        $this->assertEquals($params['a'], $route->getParam('a'));
        $this->assertFalse($route->hasParam('unknown'));
        $this->assertEquals('/test/url', $route->getUrl());
    }
}
