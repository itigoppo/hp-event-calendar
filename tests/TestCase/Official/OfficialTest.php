<?php

namespace Hanauta\HpEventCalendar\Test\TestCase\Official;

use Carbon\Carbon;
use Hanauta\HpEventCalendar\Official\Official;

/**
 * @property Official $official
 */
class OfficialTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->official = new Official();
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->official);
    }

    public function testReplaceHeader()
    {
        $method = $this->_call('replaceHeader');
        $this->assertEquals('day',$method->invoke($this->official,'日程'));
        $this->assertEquals('open_time',$method->invoke($this->official,'開場'));
        $this->assertEquals('start_time',$method->invoke($this->official,'開演'));
        $this->assertEquals('place',$method->invoke($this->official,'会場'));
        $this->assertEquals('note',$method->invoke($this->official,'備考'));
        $this->assertEquals('',$method->invoke($this->official,'その他'));
    }

    public function testFormatColumnDay()
    {
        $method = $this->_call('formatColumnDay');
        $this->assertEquals('7/7',$method->invoke($this->official,'7/7(月)'));
        $this->assertEquals('7/7',$method->invoke($this->official,'7/7(月・祝)'));
    }

    public function testFormatColumnDate()
    {
        $method = $this->_call('formatColumnDate');

        /** @var Carbon $data */
        $data = $method->invoke($this->official,'5/7','19:00');
        $this->assertEquals('5',$data->format('m'));
        $this->assertEquals('7',$data->format('d'));
        $this->assertEquals('19',$data->format('H'));
        $this->assertEquals('00',$data->format('i'));
    }

    public function testCalYear()
    {
        $method = $this->_call('calYear');
    }

    public function testGetPerformanceTimeMinutes()
    {
    }

    public function testCalPerformanceTimeMinutes()
    {
        $method = $this->_call('calPerformanceTimeMinutes');
    }


    private function _call($methodName)
    {
        $reflection = new \ReflectionClass($this->official);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}