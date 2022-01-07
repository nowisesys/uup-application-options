<?php

namespace UUP\Tests\Application\Options;

use UUP\Application\Options\ApplicationOptionsInterface;
use UUP\Application\Options\CommandLineOptions;
use PHPUnit\Framework\TestCase;

class CommandLineOptionsTest extends TestCase
{
    public function testGetOrigin()
    {
        $options = new CommandLineOptions();
        $this->assertEquals(ApplicationOptionsInterface::ORIGIN_CLI, $options->getOrigin());
    }
}
