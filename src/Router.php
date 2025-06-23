<?php

declare(strict_types=1);

namespace Slon\Http\Router;

use Psr\Http\Message\ServerRequestInterface;
use Slon\Http\Router\Contract\RouterInterface;
use Slon\Http\Router\Contract\RoutesCollectionInterface;
use Slon\Http\Router\Contract\RouteShardInterface;
use Slon\Http\Router\Exception\RouteNotFoundException;

use function in_array;

class Router implements RouterInterface
{
    public function __construct(
        protected RoutesCollectionInterface $collection,
    ) {}

    /**
     * @throws inheritDoc
     */
    public function handleRequest(
        ServerRequestInterface $request,
    ): RouteShardInterface {
        foreach ($this->collection->getCollection() as $route) {
            if (!in_array($request->getMethod(), $route->getMethods(), true)) {
                continue;
            }

            if ($routeShard = $route->match($request)) {
                return $routeShard;
            }
        }

        throw new RouteNotFoundException('Route not found');
    }

    public function generate(string $name, array $segments = []): string
    {
        $route = $this->collection->get($name);
        if ($route) {
            return $route->generate($segments);
        }
        
        throw new RouteNotFoundException('Route not found');
    }
}
