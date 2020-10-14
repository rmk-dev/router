<?php

namespace Rmk\Tests\Router;

use PHPUnit\Framework\TestCase;
use Rmk\Router\Adapter\AltoRouterAdapter;
use Rmk\Router\NotFoundRoute;
use Rmk\Router\Route;
use \AltoRouter;
use Psr\Http\Message\RequestInterface;

class AltoRouterAdapterTest extends TestCase
{

    protected $adapter;

    protected $url = '/test/url';

    protected function setUp(): void
    {
        $alto = $this->createMock(AltoRouter::class);
        $alto->method('map')->willReturn(null);
        $generalUrl = $this->url;
        $alto->method('match')
            ->willReturnCallback(static function ($url, $method) use ($generalUrl) {
                if ($url === $generalUrl) {
                    $route = new Route('test_route', $url, $method, [
                        'controller' => 'SomeController',
                        'action' => 'someAction'
                    ]);
                    return [
                        'target' => $route,
                        'name' => 'test_route',
                        'params' => []
                    ];
                }

                return null;
            });

        $alto->method('generate')->willReturn($this->url);
        $this->adapter = new AltoRouterAdapter($alto);
    }

    public function testAdd(): void
    {
        $this->assertNull($this->adapter->add(new Route('test_route', $this->url, 'GET')));
        $this->assertNull($this->adapter->add(new Route('test_route', $this->url)));
    }

    public function testMatchUrl(): void
    {
        $this->assertInstanceOf(Route::class, $this->adapter->matchUrl($this->url));
        $this->assertInstanceOf(NotFoundRoute::class, $this->adapter->matchUrl('/invalid/url'));
    }

    public function testMatch()
    {
        $request = $this->getMockForAbstractClass(RequestInterface::class);
        $request->method('getUri')->willReturn($this->url);
        $request->method('getMethod')->willReturn('GET');
        $this->assertInstanceOf(Route::class, $this->adapter->match($request));
    }

    public function testUrl()
    {
        $this->assertSame($this->url, $this->adapter->url('test_route'));
        $this->assertSame($this->url.'?a=b', $this->adapter->url('test_route', [], ['a' => 'b']));
    }
}
