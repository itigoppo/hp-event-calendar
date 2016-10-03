<?php

namespace Hanauta\HpEventCalendar\Test\TestCase\Calendar;

use Carbon\Carbon;
use Hanauta\HpEventCalendar\Calendar\Export;

/**
 * @property Export $export
 */
class ExportTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->export = new Export();
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->export);
    }


    public function testFormatForGoogleCalendar()
    {

        $information = [
            'title' => 'イベントタイトル',
            'performanceTimeMinutes' => 30,
        ];
        $eventDay = Carbon::createFromFormat('H:i', '13:00');

        $events = [
            Carbon::today()->format('Y-m-d') => [
                [
                    'start_date' => $eventDay,
                    'place' => '',
                    'note' => '',
                ],
                [
                    'start_date' => $eventDay,
                    'place' => 'イベント会場',
                    'note' => 'Aパターン',
                ],
            ],
        ];

        $data = $this->export->formatForGoogleCalendar($information, $events);

        $this->assertArrayHasKey('Subject',$data[0]);
        $this->assertArrayHasKey('Start Date',$data[0]);
        $this->assertArrayHasKey('End Date',$data[0]);
        $this->assertArrayHasKey('Start Time',$data[0]);
        $this->assertArrayHasKey('End Time',$data[0]);
        $this->assertArrayHasKey('Location',$data[0]);
        $this->assertArrayHasKey('Description',$data[0]);

        $this->assertEquals('イベントタイトル',$data[0]['Subject']);
        $this->assertEquals($eventDay->format('Y-m-d'),$data[0]['Start Date']);
        $this->assertEquals($eventDay->format('Y-m-d'),$data[0]['End Date']);
        $this->assertEquals('13:00',$data[0]['Start Time']);
        $this->assertEquals('13:30',$data[0]['End Time']);
        $this->assertNull($data[0]['Location']);
        $this->assertNull($data[0]['Description']);

        $this->assertEquals('イベント会場',$data[1]['Location']);
        $this->assertEquals('Aパターン',$data[1]['Description']);
    }
}