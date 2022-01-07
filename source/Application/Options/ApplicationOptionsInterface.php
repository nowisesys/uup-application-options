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

namespace UUP\Application\Options;

interface ApplicationOptionsInterface
{
    const ORIGIN_CLI = 1;
    const ORIGIN_HTTP = 2;

    function getScript(): string;

    function getOrigin(): int;

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
