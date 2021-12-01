<?php

declare(strict_types=1);

/*
 * Copyright (C) 2018 Anders Lövgren (Nowise Systems).
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

namespace UUP\Application\Console;

use RuntimeException;

class Terminal
{
    private string $device;
    private string $prompt;

    public function __construct(string $device = "/dev/tty", string $prompt = "> ")
    {
        $this->prompt = $prompt;
        $this->device = $device;
    }

    public function getDevice(): string
    {
        return $this->device;
    }

    public function setDevice(string $device): void
    {
        $this->device = $device;
    }

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function setPrompt(string $prompt): void
    {
        $this->prompt = $prompt;
    }

    public function password(string $prompt = null): string
    {
        try {
            system('stty -echo');
            return $this->prompt($prompt);
        } finally {
            system('stty echo');
            echo "\n";
        }
    }

    public function readline(string $prompt = null): string
    {
        return $this->prompt($prompt);
    }

    private function prompt(string $prompt = null): string
    {
        if (empty($prompt)) {
            return self::getline($this->device, $this->prompt);
        } else {
            return self::getline($this->device, $prompt);
        }
    }

    private static function getline(string $device, string $prompt): string
    {
        $response = "";

        if (!($handle = fopen($device, "r+"))) {
            throw new RuntimeException("Failed open $device");
        }

        while (strlen($response) == 0) {
            printf("$prompt");

            while ($char = fgetc($handle)) {
                if ($char == "\n") {
                    break;
                } else {
                    $response .= $char;
                }
            }

            if (strlen($response) == 0) {
                printf("\n");
            }
        }

        if (!fclose($handle)) {
            throw new RuntimeException("Failed close $device");
        }

        return $response;
    }
}
