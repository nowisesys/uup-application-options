<?php

namespace UUP\Tests\Application\Convert;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use UUP\Application\Convert\Boolean;

class BooleanTest extends TestCase
{
    public function testGetValue()
    {
        $boolean = new Boolean();
        $this->assertEquals(false, $boolean->getValue());
    }

    public function testSetValue()
    {
        $boolean = new Boolean();
        $boolean->setValue(true);
        $this->assertEquals(true, $boolean->getValue());
    }

    public function testToString()
    {
        $boolean = new Boolean(true);
        $this->assertEquals("true", $boolean->toString());

        $boolean = new Boolean(false);
        $this->assertEquals("false", $boolean->toString());
    }

    public function testThrows()
    {
        $this->expectException(InvalidArgumentException::class);
        $boolean = new Boolean("something");
    }

    /**
     * @dataProvider dataConvert
     */
    public function testConvert($value, bool $expect)
    {
        $this->assertEquals($expect, Boolean::convert($value));
    }

    public function dataConvert()
    {
        return [
            ["true", true],
            ["yes", true],
            ["on", true],
            ["1", true],
            [1, true],
            [1.0, true],
            [true, true],

            ["false", false],
            ["no", false],
            ["off", false],
            ["0", false],
            [0, false],
            [0.0, false],
            [false, false],
        ];
    }
}
