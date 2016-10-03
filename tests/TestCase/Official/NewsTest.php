<?php

namespace Hanauta\HpEventCalendar\Test\TestCase\Official;

use Hanauta\HpEventCalendar\Official\News;

/**
 * @property News $news
 */
class NewsTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->news = new News();
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->news);
    }

    public function testScrapingList()
    {
        $list = $this->news->scrapingList('OfficialNewsList.html',__DIR__.'/../../files/');

        $this->assertArrayHasKey('type', $list[0]);
        $this->assertArrayHasKey('title', $list[0]);
        $this->assertArrayHasKey('url', $list[0]);
        $this->assertArrayHasKey('units', $list[0]);
    }

    public function testScrapingNewsDetail()
    {
        $list = $this->news->scrapingList('OfficialNewsList.html',__DIR__.'/../../files/');
        $events = $this->news->scrapingNewsDetail($list[0]);

        $this->assertArrayHasKey('10/10',$events);
        $event = $events['10/10'];
        $this->assertCount(2,$event);
        $this->assertArrayHasKey('day', $event[0]);
        $this->assertArrayHasKey('place', $event[0]);
        $this->assertArrayHasKey('open_date', $event[0]);
        $this->assertArrayHasKey('start_date', $event[0]);
        $this->assertArrayHasKey('note', $event[0]);
    }
}