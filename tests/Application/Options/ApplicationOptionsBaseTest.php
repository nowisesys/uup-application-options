<?php

declare(strict_types=1);

namespace UUP\Tests\Application\Options;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use UUP\Application\Options\ApplicationOptionsBase;

class ApplicationOptionsWrapper extends ApplicationOptionsBase
{
    public function __construct()
    {
        parent::setOptions([
            'k1' => 'v1',
            'k2' => true,
            'k3' => 2.4,
            'k4' => 8
        ]);
    }

    function getScript(): string
    {
        return "";
    }

    public function getOrigin(): int
    {
        return 0;
    }
}

class ApplicationOptionsBaseTest extends TestCase
{
    public function testHasOptions()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $this->assertTrue($wrapper->hasOptions());
    }

    public function testGetOptions()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $this->assertEquals([
            'k1' => 'v1',
            'k2' => true,
            'k3' => 2.4,
            'k4' => 8
        ], $wrapper->getOptions());
    }

    public function testHasOption()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $this->assertTrue($wrapper->hasOption('k1'));
        $this->assertFalse($wrapper->hasOption('k5'));
    }

    public function testSetOption()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $this->assertEquals('v1', $wrapper->getOption('k1'));
        $wrapper->setOption('k1', 'v5');
        $this->assertEquals('v5', $wrapper->getOption('k1'));
    }

    public function testGetOption()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $this->assertEquals('v1', $wrapper->getOption('k1'));
        $this->assertEquals('v1', $wrapper->getOption('k1', 'v2'));
        $this->assertEquals('v5', $wrapper->getOption('k5', 'v5'));
    }

    public function testAddOption()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $this->assertEmpty($wrapper->getOption('k5'));

        $wrapper->addOption('k5', 'adam');
        $wrapper->addOption('k5', 'bertil');

        $this->assertNotEmpty($wrapper->getOption('k5'));
        $this->assertEquals(['adam', 'bertil'], $wrapper->getOption('k5'));
        $this->assertEquals(['adam', 'bertil'], $wrapper->getArray('k5'));

        $wrapper->setOption('k5', []);  // reset
        $this->assertEmpty($wrapper->getOption('k5'));
    }

    public function testGetString()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $this->assertEquals('v1', $wrapper->getString('k1'));
        $this->assertEquals('v1', $wrapper->getString('k1', 'v5'));
        $this->assertEquals('v5', $wrapper->getString('k5', 'v5'));

        $this->assertEquals('1', $wrapper->getString('k2'));    // bool
        $this->assertEquals('2.4', $wrapper->getString('k3'));  // float
        $this->assertEquals('8', $wrapper->getString('k4'));    // int
    }

    public function testGetFloat()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $this->assertEquals(2.4, $wrapper->getFloat('k3'));
        $this->assertEquals(2.4, $wrapper->getFloat('k3', 1.0));
        $this->assertEquals(1.0, $wrapper->getFloat('k5', 1.0));

        $this->assertEquals(0, $wrapper->getFloat('k1'));   // string
        $this->assertEquals(1, $wrapper->getFloat('k2'));   // bool
        $this->assertEquals(8, $wrapper->getFloat('k4'));   // int
    }

    public function testGetBoolean()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $this->assertEquals(true, $wrapper->getBoolean('k2'));
        $this->assertEquals(true, $wrapper->getBoolean('k2', false));
        $this->assertEquals(false, $wrapper->getBoolean('k5', false));

        $this->assertEquals(1, $wrapper->getBoolean('k3'));     // float
        $this->assertEquals(8, $wrapper->getBoolean('k4'));     // int

        $this->expectException(InvalidArgumentException::class);
        $this->assertEquals(true, $wrapper->getBoolean('k1'));  // string
    }

    public function testGetInteger()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $this->assertEquals(8, $wrapper->getInteger('k4'));
        $this->assertEquals(8, $wrapper->getInteger('k4', 6));
        $this->assertEquals(6, $wrapper->getInteger('k5', 6));

        $this->assertEquals(0, $wrapper->getInteger('k1'));   // string
        $this->assertEquals(1, $wrapper->getInteger('k2'));   // bool
        $this->assertEquals(2, $wrapper->getInteger('k3'));   // float
    }

    public function testGetArray()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $this->assertEquals([], $wrapper->getArray('k5'));
        $this->assertEquals(['adam', 'bertil'], $wrapper->getArray('k5', ['adam', 'bertil']));

        $wrapper->setOption('k5', ['adam', 'bertil']);
        $this->assertEquals(['adam', 'bertil'], $wrapper->getArray('k5'));
    }

    public function testGetObject()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $this->assertNull($wrapper->getObject('k5'));
    }

    public function testSetObject()
    {
        $wrapper = new ApplicationOptionsWrapper();
        $wrapper->setObject('k5', new stdClass());

        $this->assertNotNull($wrapper->getObject('k5'));
        $this->assertIsObject($wrapper->getObject('k5'));
    }

    public function testIsMissing()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $this->assertFalse($wrapper->isMissing('k1'));
        $this->assertTrue($wrapper->isMissing('k5'));
    }

    public function testIsObject()
    {
        $wrapper = new ApplicationOptionsWrapper();

        $wrapper->setOption('k5', 'v5');
        $this->assertFalse($wrapper->isObject('k5'));

        $wrapper->setObject('k5', (object)[]);
        $this->assertTrue($wrapper->isObject('k5'));
    }
}
