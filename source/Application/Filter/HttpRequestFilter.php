<?php

declare(strict_types=1);

namespace UUP\Application\Filter;

class HttpRequestFilter implements FilterInterface
{
    private array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(array $filters): void
    {
        $this->filters = $filters;
    }

    public function setFilter(string $key, int $filter = FILTER_DEFAULT): void
    {
        $this->filters[$key] = $filter;
    }

    public function getFilter(string $key): int
    {
        if (array_key_exists($key, $this->filters)) {
            return $this->filters[$key];
        } else {
            return FILTER_DEFAULT;
        }
    }

    public function apply(array $options): array
    {
        foreach ($options as $key => $value) {
            $options[$key] = $this->getCleaned($key, $value);
        }

        return $options;
    }

    private function getCleaned(string $key, $value)
    {
        return filter_var($value, $this->getFilter($key));
    }
}
