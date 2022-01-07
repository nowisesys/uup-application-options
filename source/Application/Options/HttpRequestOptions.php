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

use UUP\Application\Filter\HttpRequestFilter;

class HttpRequestOptions extends ApplicationOptionsBase implements ApplicationOptionsInterface
{
    public function __construct(HttpRequestFilter $filters = null)
    {
        parent::setOptions($this->getParameters($_GET, $filters));
    }

    public function getScript(): string
    {
        return basename(filter_input(INPUT_SERVER, 'PHP_SELF'));
    }

    public function getOrigin(): int
    {
        return self::ORIGIN_HTTP;
    }

    private function getParameters(array $options, ?HttpRequestFilter $filter): array
    {
        if (isset($filter)) {
            return $filter->apply($options);
        } else {
            return $options;
        }
    }
}
