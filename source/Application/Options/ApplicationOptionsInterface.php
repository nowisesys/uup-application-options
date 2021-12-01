<?php

declare(strict_types=1);

namespace UUP\Application\Options;

interface ApplicationOptionsInterface
{
    function getScript(): string;

    function hasOptions(): bool;

    function getOptions(): array;

    function hasOption(string $name): bool;

    function getOption(string $name, $default = null);

    function setOption(string $name, $value): void;

    function addOption(string $name, $value): void;

    function getString(string $name, string $default = ""): string;

    function getFloat(string $name, float $default = 0.0): float;

    function getInteger(string $name, int $default = 0): int;

    function getBoolean(string $name, bool $default = false): bool;

    function getArray(string $name, array $default = []): array;

    function getObject(string $name);

    function setObject(string $name, object $object);

    function isMissing(string $name): bool;

    function isObject(string $name): bool;
}
