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

class CommandLineOptions extends ApplicationOptionsBase implements ApplicationOptionsInterface
{
    public function __construct(array $mapping = [])
    {
        parent::setOptions($this->getParameters($_SERVER['argv'], $mapping));
    }

    public function getScript(): string
    {
        return basename($_SERVER['argv'][0]);
    }

    private function getParameters(array $argv, array $mapping): array
    {
        $result = [];

        foreach ($argv as $args) {
            if (strstr($args, '=')) {
                list($key, $val) = explode('=', $args);
            } else {
                list($key, $val) = [$args, true];
            }

            if ($key == '-h') {
                $key = 'help';
            }
            if ($key == '-V') {
                $key = 'version';
            }
            if ($key == '-v') {
                $key = 'verbose';
            }
            if ($key == '-d') {
                $key = 'debug';
            }
            if ($key == '-q') {
                $key = 'quiet';
            }

            if (array_key_exists($key, $mapping)) {
                $key = $mapping[$key];
            }

            if ($key[0] == '-' && $key[1] == '-') {
                $key = substr($key, 2);
            } elseif ($key[0] == '/' && $key[1] == ':') {
                $key = substr($key, 2);
            } elseif ($key[0] == '-') {
                $key = substr($key, 1);
            } elseif ($key == '/?') {
                $key = 'help';
            }

            $result[$key] = $val;
        }

        return $result;
    }
}
