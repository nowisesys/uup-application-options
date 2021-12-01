<?php

declare(strict_types=1);

namespace UUP\Application\Filter;

interface FilterInterface
{
    public function apply(array $options): array;
}
