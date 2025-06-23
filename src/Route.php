<?php

declare(strict_types=1);

namespace Slon\Http\Router;

use const ARRAY_FILTER_USE_KEY;

use Psr\Http\Message\ServerRequestInterface;
use Slon\Http\Router\Contract\RouteInterface;
use Slon\Http\Router\Contract\RouteShardInterface;

use function array_filter;
use function preg_match;
use function sprintf;
use function str_replace;

class Route implements RouteInterface
{
    public function __construct(
        protected string $name,
        protected string $rule,
        protected string $handler,
        protected array $segments = [],
        protected array $methods = ['GET', 'POST'],
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getRule(): string
    {
        return $this->rule;
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function getSegments(): array
    {
        return $this->segments;
    }

    /**
     * @inheritDoc
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    public function match(ServerRequestInterface $request): ?RouteShardInterface
    {
        $rule = $this->getRule();
        foreach ($this->getSegments() as $id => $regEx) {
            $rule = str_replace(
                sprintf('{%s}', $id),
                sprintf('(?P<%s>%s)', $id, $regEx),
                $rule
            );
        }

        $matches = [];
        return ((bool) preg_match(
                sprintf('~^%s$~', $rule),
                $request->getUri()->getPath(),
                $matches,
            ))
            ? new RouteShard(
                $this->getHandler(),
                array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY),
            )
            : null;
    }

    public function generate(array $segments = []): string
    {
        $rule = $this->getRule();
        foreach ($segments as $id => $segment) {
            $rule = str_replace(sprintf('{%s}', $id), (string)$segment, $rule);
        }

        $matches = [];
        preg_match('(\(/?\{[a-z_-]+\}/?\)\?)', $rule, $matches);
        foreach ($matches as $match) {
            $rule = str_replace($match, '', $rule);
        }

        return str_replace([')?', '('], ['', ''], $rule);
    }
}
