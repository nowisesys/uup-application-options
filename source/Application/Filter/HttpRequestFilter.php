<?php

/*
 * Copyright (C) 2021 Anders Lövgren (Nowise Systems).
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
