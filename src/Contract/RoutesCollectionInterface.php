<?php

declare(strict_types=1);

namespace Slon\Http\Router\Contract;

interface RoutesCollectionInterface
{
    public function add(RouteInterface ...$route): void;

    public function get(string $name): ?RouteInterface;

    /**
     * @return list<RouteInterface>
     */
    public function getCollection(): array;
}
