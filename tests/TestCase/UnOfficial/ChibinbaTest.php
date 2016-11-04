<?php

namespace Hanauta\HpEventCalendar\Test\TestCase\UnOfficial;

use Hanauta\HpEventCalendar\UnOfficial\Chibinba;

/**
 * @property Chibinba $site
 */
class ChibinbaTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->site = new Chibinba();
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->site);
    }

    public function testScrapingList()
    {
        $list = $this->site->scrapingList(Chibinba::DOMAIN_HP . '/list.cgi');

    }

}