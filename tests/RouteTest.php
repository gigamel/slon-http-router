<?php

declare(strict_types=1);

namespace Slon\Http\Router\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slon\Http\Router\Contract\RouteInterface;
use Slon\Http\Router\Contract\RouteShardInterface;
use Slon\Http\Router\Route;

class RouteTest extends TestCase
{
    public static function matchDataProvider(): array
    {
        return [
            'fullPath' => [
                'uri' => '/some/page/10/more/100',
            ],
            'shortPath' => [
                'uri' => '/some/page/more/100',
            ],
        ];
    }

    public function testGenerate(): void
    {
        $route = $this->makeRoute();

        $this->assertEquals(
            $route->generate(['page' => 10, 'id' => 100]),
            '/some/page/10/more/100'
        );

        $this->assertEquals(
            $route->generate(['id' => 100]),
            '/some/page/more/100'
        );
    }

    #[DataProvider('matchDataProvider')]
    public function testMatch(string $uri): void
    {
        $route = $this->makeRoute();

        $request = $this->createStub(ServerRequestInterface::class);
        
        $uriStub = $this->createStub(UriInterface::class);
        
        $uriStub
            ->method('getPath')
            ->willReturn($uri);
        
        $request
            ->method('getUri')
            ->willReturn($uriStub);

        $routeShard = $route->match($request);
        $this->assertInstanceOf(RouteShardInterface::class, $routeShard);
    }

    private function makeRoute(): RouteInterface
    {
        return new Route(
            'general',
            '/some/page(/{page})?/more/{id}',
            'Handler',
            [
                'page' => '\d+',
                'id' => '\d+',
            ],
        );
    }
}
