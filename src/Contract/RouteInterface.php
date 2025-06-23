<?php

declare(strict_types=1);

namespace Slon\Http\Router\Contract;

use Psr\Http\Message\ServerRequestInterface;

interface RouteInterface
{
    public function getName(): string;

    public function getRule(): string;

    public function getHandler(): string;

    /**
     * @return array<string, string|float|int>
     */
    public function getSegments(): array;

    /**
     * @return list<string>
     */
    public function getMethods(): array;

    public function match(
        ServerRequestInterface $request,
    ): ?RouteShardInterface;

    /**
     * @return array<string, mixed>
     */
    public function generate(array $segments = []): string;
}
