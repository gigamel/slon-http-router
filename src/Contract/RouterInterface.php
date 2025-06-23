<?php

declare(strict_types=1);

namespace Slon\Http\Router\Contract;

use Psr\Http\Message\ServerRequestInterface;
use Slon\Http\Router\Exception\RouteNotFoundException;

interface RouterInterface
{
    /**
     * @throws RouteNotFoundException
     */
    public function handleRequest(
        ServerRequestInterface $request,
    ): RouteShardInterface;

    /**
     * @param array<string, string|int|float> $segments
     *
     * @throws RouteNotFoundException
     */
    public function generate(string $name, array $segments = []): string;
}
