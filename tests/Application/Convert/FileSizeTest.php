<?php

namespace UUP\Tests\Application\Convert;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use UUP\Application\Convert\FileSize;

class FileSizeTest extends TestCase
{
    /**
     * @dataProvider dataFromString
     */
    public function testFromString(string $size, $expect)
    {
        $filesize = new FileSize();
        $this->assertEquals($expect, $filesize->fromString($size));
    }

    /**
     * @dataProvider dataToString
     */
    public function testToString(float $size, bool $si, int $precision, bool $approx, bool $compact, string $expect)
    {
        $filesize = new FileSize();

        $filesize->setPrecision($precision);
        $filesize->setApprox($approx);
        $filesize->setCompact($compact);

        $this->assertEquals($expect, $filesize->toString($size, $si));
    }

    /**
     * @dataProvider dataToString
     */
    public function testConstructor(float $size, bool $si, int $precision, bool $approx, bool $compact, string $expect)
    {
        $filesize = new FileSize($precision, $approx, $compact);

        $this->assertEquals($expect, $filesize->toString($size, $si));
    }

    /**
     * @dataProvider dataThrowsException
     */
    public function testThrowsException(string $size)
    {
        $filesize = new FileSize();
        $this->expectException(InvalidArgumentException::class);
        $filesize->fromString($size);
    }

    public function dataFromString(): array
    {
        return [
            //
            // Binary suffix:
            //
            ["1B", 1],

            ["1K", pow(2, 10)],
            ["1KB", pow(2, 10)],
            ["1KiB", pow(2, 10)],

            ["1M", pow(2, 20)],
            ["1MiB", pow(2, 20)],

            ["1G", pow(2, 30)],
            ["1GiB", pow(2, 30)],

            ["1T", pow(2, 40)],
            ["1TiB", pow(2, 40)],

            ["1P", pow(2, 50)],
            ["1PiB", pow(2, 50)],

            ["1E", pow(2, 60)],
            ["1EiB", pow(2, 60)],

            ["1Z", pow(2, 70)],
            ["1ZiB", pow(2, 70)],

            ["1Y", pow(2, 80)],
            ["1YiB", pow(2, 80)],

            //
            // SI-suffix:
            //
            ["1k", pow(10, 3)],
            ["1kB", pow(10, 3)],

            ["1MB", pow(10, 6)],
            ["1GB", pow(10, 9)],
            ["1TB", pow(10, 12)],
            ["1PB", pow(10, 15)],
            ["1EB", pow(10, 18)],
            ["1ZB", pow(10, 21)],
            ["1YB", pow(10, 24)],

            //
            // Test float values:
            //
            ["1.23MB", 1.23 * pow(10, 6)],
            ["4096.0MB", 4096 * pow(10, 6)],

            //
            // Test with space:
            //
            ["4096 MB", 4096 * pow(10, 6)],
            ["4096  MB", 4096 * pow(10, 6)],
        ];
    }

    public function dataToString(): array
    {
        return [
            //
            // Test bytes:
            //
            [1, false, 2, false, false, "1"],
            [1, true, 2, false, false, "1"],

            //
            // Test exact:
            //
            [pow(2, 10), false, 2, false, false, "1.00 KiB"],
            [pow(10, 3), true, 2, false, false, "1.00 KB"],

            [pow(2, 20), false, 2, false, false, "1.00 MiB"],
            [pow(10, 6), true, 2, false, false, "1.00 MB"],

            [pow(2, 30), false, 2, false, false, "1.00 GiB"],
            [pow(10, 9), true, 2, false, false, "1.00 GB"],

            [pow(2, 40), false, 2, false, false, "1.00 TiB"],
            [pow(10, 12), true, 2, false, false, "1.00 TB"],

            [pow(2, 50), false, 2, false, false, "1.00 PiB"],
            [pow(10, 15), true, 2, false, false, "1.00 PB"],

            [pow(2, 60), false, 2, false, false, "1.00 EiB"],
            [pow(10, 18), true, 2, false, false, "1.00 EB"],

            [pow(2, 70), false, 2, false, false, "1.00 ZiB"],
            [pow(10, 21), true, 2, false, false, "1.00 ZB"],

            [pow(2, 80), false, 2, false, false, "1.00 YiB"],
            [pow(10, 24), true, 2, false, false, "1.00 YB"],

            [pow(2, 10) - 1, false, 2, false, false, "1023"],
            [pow(10, 3) - 1, true, 2, false, false, "999"],

            [pow(2, 20) - 1, false, 2, false, false, "1024.00 KiB"],
            [pow(10, 6) - 1, true, 2, false, false, "1000.00 KB"],

            //
            // Test approx:
            //
            [pow(2, 10), false, 2, true, false, "1.00 KiB"],
            [pow(10, 3), true, 2, true, false, "1.00 KB"],

            [pow(2, 20), false, 2, true, false, "1.00 MiB"],
            [pow(10, 6), true, 2, true, false, "1.00 MB"],

            [pow(2, 30), false, 2, true, false, "1.00 GiB"],
            [pow(10, 9), true, 2, true, false, "1.00 GB"],

            [pow(2, 40), false, 2, true, false, "1.00 TiB"],
            [pow(10, 12), true, 2, true, false, "1.00 TB"],

            [pow(2, 50), false, 2, true, false, "1.00 PiB"],
            [pow(10, 15), true, 2, true, false, "1.00 PB"],

            [pow(2, 60), false, 2, true, false, "1.00 EiB"],
            [pow(10, 18), true, 2, true, false, "1.00 EB"],

            [pow(2, 70), false, 2, true, false, "1.00 ZiB"],
            [pow(10, 21), true, 2, true, false, "1.00 ZB"],

            [pow(2, 80), false, 2, true, false, "1.00 YiB"],
            [pow(10, 24), true, 2, true, false, "1.00 YB"],

            [pow(2, 10) - 1, false, 2, true, false, "1.00 KiB"],
            [pow(10, 3) - 1, true, 2, true, false, "1.00 KB"],

            [pow(2, 20) - 1, false, 2, true, false, "1.00 MiB"],
            [pow(10, 6) - 1, true, 2, true, false, "1.00 MB"],

            //
            // Test precision:
            //
            [pow(2, 20) - 1, false, 0, true, false, "1 MiB"],
            [pow(10, 6) - 1, true, 0, true, false, "1 MB"],
            [pow(2, 20) - 1, false, 1, true, false, "1.0 MiB"],
            [pow(10, 6) - 1, true, 3, true, false, "1.000 MB"],
            [pow(10, 6) - 1, true, 5, true, false, "1.00000 MB"],

            //
            // Test compact:
            //
            [pow(2, 20) - 1, false, 0, true, true, "1MiB"],
            [pow(10, 6) - 1, true, 0, true, true, "1MB"],
        ];
    }

    public function dataThrowsException(): array
    {
        return [
            ["1kb"],
            ["1OB"],
            ["1mb"]
        ];
    }
}
