<?php
namespace Hanauta\HpEventCalendar\Calendar;

use Carbon\Carbon;

class Export
{
    const HEADER_TITLE       = 'Subject';
    const HEADER_START_DATE  = 'Start Date';
    const HEADER_END_DATE    = 'End Date';
    const HEADER_START_TIME  = 'Start Time';
    const HEADER_END_TIME    = 'End Time';
    const HEADER_LOCATION    = 'Location';
    const HEADER_DESCRIPTION = 'Description';
    const HEADER_ALL_DAY     = 'All Day Event';

    /**
     * Googleカレンダーのインポート形式でフォーマット
     * @param array $information
     * @param array $days
     * @return array
     */
    public function formatForGoogleCalendar($information, $days)
    {
        $data = [];
        foreach ($days as $events) {
            foreach ($events as $event) {
                /** @var Carbon $start_date */
                $start_date = $event['start_date'];
                /** @var Carbon $end_date */
                $end_date = clone $start_date;
                $end_date->addMinutes($information['performanceTimeMinutes']);

                $val = [
                    self::HEADER_TITLE => $information['title'],
                    self::HEADER_START_DATE => $start_date->format('Y-m-d'),
                    self::HEADER_END_DATE => $end_date->format('Y-m-d'),
                    self::HEADER_START_TIME => $start_date->format('H:i'),
                    self::HEADER_END_TIME => $end_date->format('H:i'),
                    self::HEADER_LOCATION => empty($event['place']) ? null : $event['place'],
                    self::HEADER_DESCRIPTION => empty($event['note']) ? null : $event['note'],
                ];

                $data[] = $val;
            }
        }

        return $data;
    }
}