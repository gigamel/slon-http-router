<?php

declare(strict_types=1);

namespace Slon\Http\Router\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slon\Http\Router\Contract\RouterInterface;
use Slon\Http\Router\Contract\RouteShardInterface;
use Slon\Http\Router\Route;
use Slon\Http\Router\Router;
use Slon\Http\Router\RoutesCollection;

class RouterTest extends TestCase
{
    public static function handleRequestDataProvider(): array
    {
        return [
            'fullPath' => [
                'path' => '/some/page/10/more/100',
            ],
            'shortPath' => [
                'path' => '/some/page/more/100',
            ],
            'hardPath' => [
                'path' => '/some/more/100',
                'rule' => '/some/(page/{page}/)?more/100',
            ],
        ];
    }

    public function testGenerate(): void
    {
        $router = $this->makeRouter();

        $this->assertEquals(
            $router->generate('general', ['page' => 10, 'id' => 100]),
            '/some/page/10/more/100'
        );

        $this->assertEquals(
            $router->generate('general', ['id' => 100]),
            '/some/page/more/100'
        );
    }

    #[DataProvider('handleRequestDataProvider')]
    public function testHandleClientMessage(
        string $path,
        ?string $rule = null
    ): void {
        $request = $this->createStub(ServerRequestInterface::class);
        
        $uriStub = $this->createStub(UriInterface::class);
        
        $uriStub
            ->method('getPath')
            ->willReturn($path);

        $request
            ->method('getUri')
            ->willReturn($uriStub);

        $request
            ->method('getMethod')
            ->willReturn('GET');

        $router = $this->makeRouter($rule);

        $routeShard = $router->handleRequest($request);

        $this->assertInstanceOf(RouteShardInterface::class, $routeShard);
    }

    private function makeRouter(?string $rule = null): RouterInterface
    {
        $routesCollection = new RoutesCollection();

        $routesCollection->add(
            new Route(
                'general',
                $rule ?? '/some/page(/{page})?/more/{id}',
                'Handler',
                [
                    'page' => '\d+',
                    'id' => '\d+',
                ]
            )
        );

        return new Router($routesCollection);
    }
}
