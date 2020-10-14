<?php

namespace Rmk\Tests\Router;

use PHPUnit\Framework\TestCase;
use Rmk\Router\Adapter\RouterAdapterInterface;
use Rmk\Router\Exception\RouterConfigException;
use Rmk\Router\NotFoundRoute;
use Rmk\Router\Route;
use Rmk\Router\RouterService;
use Psr\Http\Message\RequestInterface;

class RouterServiceTest extends TestCase
{

    protected $service;

    protected $url = '/test/url';

    protected $adapter;

    protected function setUp(): void
    {
        $this->adapter = $this->getMockForAbstractClass(RouterAdapterInterface::class);
        $this->adapter->method('add')->willReturn(null);
        $generalUrl = $this->url;
        $matchCallback = static function($url, $method) use ($generalUrl) {
            if ($url === $generalUrl) {
                return new Route('test_route', $url, $method, [
                    'controller' => 'SomeController',
                    'action' => 'someAction'
                ]);
            }

            return new NotFoundRoute('', $url, $method);
        };
        $matchUrlCallback = [$this->adapter, 'matchUrl'];
        $this->adapter->method('match')
            ->willReturnCallback(static function ($request) use ($matchUrlCallback) {
                return $matchUrlCallback($request->getUri() . '', $request->getMethod());
            });

        $this->adapter->method('matchUrl')->willReturnCallback($matchCallback);

        $this->adapter->method('url')->willReturnCallback(static function (
            $name,
            $params = [],
            $query = null,
            $full = false
        ) use ($generalUrl) {
            $url = $generalUrl;
            if ($query) {
                $url .= '?' . http_build_query($query);
            }
            return $url;
        });
        $this->service = new RouterService($this->adapter);
    }

    public function testGetter(): void
    {
        $this->assertSame($this->adapter, $this->service->getRouterAdapter());
    }

    public function testAdd(): void
    {
        $this->assertNull($this->service->add(new Route('test_route', $this->url, 'GET')));
        $this->assertNull($this->service->add(new Route('test_route', $this->url)));
    }

    /**
     * @dataProvider configProvider
     * @param $name
     * @param $config
     */
    public function testCreateRoute($name, $config): void
    {
        $this->assertInstanceOf(Route::class, $this->service->createRoute($name, $config));
    }

    /**
     * @depends testCreateRoute
     */
    public function testLoadFromConfig(): void
    {
        $config = [
            'test_route' => ['url' => '/test/url', 'handler' => 'testHandler'],
            'test_route2' => ['url' => '/test/url/1', 'handler' => 'testHandler'],
            'test_route3' => ['url' => '/test/url/:id', 'handler' => 'testHandler', 'params' => ['id' => '\d']]
        ];
        $this->assertNull($this->service->loadFromConfig($config));
    }

    public function testMatchUrl(): void
    {
        $this->assertInstanceOf(Route::class, $this->service->matchUrl($this->url));
        $this->assertInstanceOf(NotFoundRoute::class, $this->service->matchUrl('/invalid/url'));
    }

    public function testMatch(): void
    {
        $request = $this->getMockForAbstractClass(RequestInterface::class);
        $request->method('getUri')->willReturn($this->url);
        $request->method('getMethod')->willReturn('GET');
        $this->assertInstanceOf(Route::class, $this->service->match($request));
    }

    public function testUrl(): void
    {
        $this->assertSame($this->url, $this->service->url('test_route'));
        $this->assertSame($this->url.'?a=b', $this->service->url('test_route', [], ['a' => 'b']));
    }

    public function testPreventCreateWithoutUrl(): void
    {
        $this->expectException(RouterConfigException::class);
        $this->expectExceptionCode(1);
        $this->service->createRoute('test', ['handler' => 'testHandler']);
    }

    public function testPreventCreateWithoutHandler(): void
    {
        $this->expectException(RouterConfigException::class);
        $this->expectExceptionCode(2);
        $this->service->createRoute('test', ['url' => $this->url]);
    }

    public function configProvider(): array
    {
        return [
            ['test_route', ['url' => '/test/url', 'handler' => 'testHandler']],
            ['test_route2', ['url' => '/test/url/1', 'handler' => 'testHandler']], 
            ['test_route3', ['url' => '/test/url/:id', 'handler' => 'testHandler', 'params' => ['id' => '\d']]],
        ];
    }
}