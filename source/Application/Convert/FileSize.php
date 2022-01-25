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

namespace UUP\Application\Convert;

//
// See https://sv.wikipedia.org/wiki/Byte for more information.
//

use InvalidArgumentException;

class FileSize
{
    private int $precision;
    private bool $approx;
    private bool $compact;

    public function __construct(int $precision = 2, bool $approx = true, bool $compact = false)
    {
        $this->precision = $precision;
        $this->approx = $approx;
        $this->compact = $compact;
    }

    public function setPrecision(int $precision): void
    {
        $this->precision = $precision;
    }

    public function setApprox(bool $approx = true): void
    {
        $this->approx = $approx;
    }

    public function setCompact(bool $compact = true): void
    {
        $this->compact = $compact;
    }

    public function fromString(string $size): float
    {
        if (preg_match('/^([\d.]+)\s*(B|K|M|G|T|P|E|Z|Y|KB|KiB|MiB|GiB|TiB|PiB|EiB|ZiB|YiB|k|kB|MB|GB|TB|PB|EB|ZB|YB)$/', $size, $matches)) {

            switch ($matches[2]) {
                //
                // Binary suffix:
                //
                case 'B':
                    return $matches[1] * pow(2, 0);
                case 'K':
                case 'KiB':
                case 'KB':
                    return $matches[1] * pow(2, 10);
                case 'M':
                case 'MiB':
                    return $matches[1] * pow(2, 20);
                case 'G':
                case 'GiB':
                    return $matches[1] * pow(2, 30);
                case 'T':
                case 'TiB':
                    return $matches[1] * pow(2, 40);
                case 'P':
                case 'PiB':
                    return $matches[1] * pow(2, 50);
                case 'E':
                case 'EiB':
                    return $matches[1] * pow(2, 60);
                case 'Z':
                case 'ZiB':
                    return $matches[1] * pow(2, 70);
                case 'Y':
                case 'YiB':
                    return $matches[1] * pow(2, 80);

                //
                // SI-suffix:
                //
                case 'k':
                case 'kB':
                    return $matches[1] * pow(10, 3);
                case 'MB':
                    return $matches[1] * pow(10, 6);
                case 'GB':
                    return $matches[1] * pow(10, 9);
                case 'TB':
                    return $matches[1] * pow(10, 12);
                case 'PB':
                    return $matches[1] * pow(10, 15);
                case 'EB':
                    return $matches[1] * pow(10, 18);
                case 'ZB':
                    return $matches[1] * pow(10, 21);
                case 'YB':
                    return $matches[1] * pow(10, 24);

                default:
                    throw new InvalidArgumentException(sprintf("Unhandled suffix %s", $matches[2]));
            }
        }

        if (preg_match('/^[\d.]+(.+)$/', $size, $matches)) {
            throw new InvalidArgumentException(sprintf("Unexpected size suffix %s", $matches[1]));
        }

        return floatval($size);
    }

    public function toString(float $size, bool $si = false): string
    {
        $suffixes = ['', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'];
        $position = 0;

        if ($si) {
            $divisor = pow(10, 3);
        } else {
            $divisor = pow(2, 10);
        }

        if ($this->approx) {
            while ($size > 1) {
                $size /= $divisor;
                $position++;
            }
        } else {
            while ($size >= $divisor) {
                $size /= $divisor;
                $position++;
            }
        }

        if ($position == 0) {
            return sprintf("%d", $size);
        }

        if ($si) {
            $suffixes[0] = 'k';     // Special case for SI-prefix
        }

        return sprintf(             // Compatible with PHP < 8.0
            $this->getFormatString($si), $size, $suffixes[$position]
        );
    }

    private function getFormatString(bool $si): string
    {
        if ($this->compact) {
            if ($si) {
                return "%.0{$this->precision}f%sB";
            } else {
                return "%.0{$this->precision}f%siB";
            }
        } else {
            if ($si) {
                return "%.0{$this->precision}f %sB";
            } else {
                return "%.0{$this->precision}f %siB";
            }
        }
    }
}
