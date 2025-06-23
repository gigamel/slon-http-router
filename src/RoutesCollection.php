<?php

declare(strict_types=1);

namespace Slon\Http\Router;

use Slon\Http\Router\Contract\RoutesCollectionInterface;
use Slon\Http\Router\Contract\RouteInterface;

class RoutesCollection implements RoutesCollectionInterface
{
    /** @var list<RouteInterface> */
    protected array $collection = [];

    public function add(RouteInterface ...$route): void
    {
        $this->collection = [...$this->collection, ...$route];
    }

    public function get(string $name): ?RouteInterface
    {
        foreach ($this->getCollection() as $route) {
            if ($name === $route->getName()) {
                return $route;
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getCollection(): array
    {
        return $this->collection;
    }
}
