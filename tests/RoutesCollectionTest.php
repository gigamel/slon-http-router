<?php

declare(strict_types=1);

namespace Slon\Http\Router\Tests;

use PHPUnit\Framework\TestCase;
use Slon\Http\Router\Route;
use Slon\Http\Router\Contract\RouteInterface;
use Slon\Http\Router\RoutesCollection;

class RoutesCollectionTest extends TestCase
{
    public function testGetRoute(): void
    {
        $routesCollection = new RoutesCollection();

        $routesCollection->add(
            new Route(
                'general',
                '/some/page(/{page})?/more/{id}',
                'Handler'
            ),
        );

        $route = $routesCollection->get('general');

        $this->assertInstanceOf(RouteInterface::class, $route);

        $this->assertEquals($route->getName(), 'general');
        $this->assertEquals($route->getRule(), '/some/page(/{page})?/more/{id}');
        $this->assertEquals($route->getHandler(), 'Handler');
    }
}
