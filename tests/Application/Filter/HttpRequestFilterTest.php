<?php

namespace UUP\Tests\Application\Filter;

use PHPUnit\Framework\TestCase;
use UUP\Application\Filter\HttpRequestFilter;

class HttpRequestFilterTest extends TestCase
{
    public function testConstruct()
    {
        $filter = new HttpRequestFilter([
            'email' => FILTER_SANITIZE_EMAIL
        ]);
        $this->assertNotEmpty($filter->getFilters());
    }

    public function testGetFilters()
    {
        $filter = new HttpRequestFilter();
        $this->assertEmpty($filter->getFilters());
    }

    public function testSetFilters()
    {
        $filter = new HttpRequestFilter();
        $filter->setFilters([
            'email' => FILTER_SANITIZE_EMAIL
        ]);
        $this->assertNotEmpty($filter->getFilters());
    }

    public function testGetFilter()
    {
        $filter = new HttpRequestFilter();
        $this->assertEquals(FILTER_DEFAULT, $filter->getFilter('email'));
    }

    public function testSetFilter()
    {
        $filter = new HttpRequestFilter();
        $filter->setFilter('email', FILTER_SANITIZE_EMAIL);
        $this->assertEquals(FILTER_SANITIZE_EMAIL, $filter->getFilter('email'));
    }

    public function testApply()
    {
        $filter = new HttpRequestFilter();

        $filter->setFilter('email', FILTER_SANITIZE_EMAIL);
        $this->assertEquals([
            'email' => 'user@example.com'
        ], $filter->apply([
            'email' => 'user@example.com'
        ]));

        $this->assertEquals([
            'email' => 'user@example.com'
        ], $filter->apply([
            'email' => 'user@example\\.com'
        ]));

        $filter->setFilter('email', FILTER_VALIDATE_EMAIL);
        $this->assertEquals([
            'email' => false
        ], $filter->apply([
            'email' => 'user@example\\.com'
        ]));
    }

}
