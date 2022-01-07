<?php

namespace UUP\Tests\Application\Options;

use UUP\Application\Options\ApplicationOptionsInterface;
use UUP\Application\Options\HttpRequestOptions;
use PHPUnit\Framework\TestCase;

class HttpRequestOptionsTest extends TestCase
{
    public function testGetOrigin()
    {
        $options = new HttpRequestOptions();
        $this->assertEquals(ApplicationOptionsInterface::ORIGIN_HTTP, $options->getOrigin());
    }
}
