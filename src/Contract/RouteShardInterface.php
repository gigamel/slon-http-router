<?php

declare(strict_types=1);

namespace Slon\Http\Router\Contract;

interface RouteShardInterface
{
    public function getHandler(): string;

    /**
     * @return array<string, string|float|int>
     */
    public function getSegments(): array;
}
