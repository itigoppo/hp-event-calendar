<?php
namespace Hanauta\HpEventCalendar\Official;

use Carbon\Carbon;

class Official
{
    const DOMAIN_HP = 'http://www.helloproject.com';

    // ヘッダーキー
    const HEADER_DAY        = 'day';
    const HEADER_OPEN_TIME  = 'open_time';
    const HEADER_OPEN_DATE  = 'open_date';
    const HEADER_START_TIME = 'start_time';
    const HEADER_START_DATE = 'start_date';
    const HEADER_PLACE      = 'place';
    const HEADER_NOTE       = 'note';

    // 昼夜公演の間
    const INTERVAL_MINUTES = 60;

    /**
     * ヘッダーをキーとして取得
     * @param string $val
     * @return string
     */
    protected function replaceHeader($val)
    {
        switch ($val) {
            case '日程':
                return self::HEADER_DAY;
            case '会場':
                return self::HEADER_PLACE;
            case '開場':
                return self::HEADER_OPEN_TIME;
            case '開演':
                return self::HEADER_START_TIME;
            case '備考':
                return self::HEADER_NOTE;
            default :
                return '';
        }
    }

    /**
     * 「M/D(W)」から日付のみにする
     * @param string $text
     * @return string
     */
    protected function formatColumnDay($text)
    {
        $pos = strpos($text, '(');
        if ($pos !== false) {
            return substr($text, 0, $pos);
        };

        return $text;
    }

    /**
     * 「M/D」＋「H:i」から日付データにする
     * @param string $day
     * @param string $time
     * @return Carbon
     */
    protected function formatColumnDate($day, $time)
    {
        list($month, $day) = explode('/', $day);
        $year = $this->calYear($month, 5);

        return Carbon::createFromFormat('Y/m/d H:i', $year . '/' . $month . '/' . $day . ' ' . $time);
    }

    /**
     * 開催月が今の月よりnヶ月若いときは来年
     * @param int $month
     * @param int $ago
     * @return int
     */
    protected function calYear($month, $ago)
    {
        $today = Carbon::today();

        $agoData = clone $today;
        $agoData->subMonths($ago);

        if ($today->format('m') > $month) {
            return $today->addYear()->year;
        }

        return $today->year;
    }

    /**
     * 公演時間を取得
     * @param array $events
     * @return int
     */
    public function getPerformanceTimeMinutes($events)
    {
        $performanceTimeMinutes = 0;
        foreach ($events as $event) {
            if (count($event) == 2) {
                $performanceTimeMinutes = $this->calPerformanceTimeMinutes($event[0][self::HEADER_START_DATE],
                    $event[1][self::HEADER_OPEN_DATE]);
            }
            if ($performanceTimeMinutes != 0) {
                break;
            }
        }
        if ($performanceTimeMinutes == 0) {
            // 1日2公演のないときは1時間半
            $performanceTimeMinutes = (60 * 2) + 30;
        }

        return $performanceTimeMinutes;
    }

    /**
     * 1部の開演時間、2部の開場時間から公演時間を計算する
     * @param Carbon $startDate
     * @param Carbon $openDate
     * @return int
     */
    protected function calPerformanceTimeMinutes(Carbon $startDate, Carbon $openDate)
    {
        $diffMinutes = $startDate->diffInMinutes($openDate);

        $performanceTimeMinutes = $diffMinutes - self::INTERVAL_MINUTES;

        do {
            $endDate = clone $startDate;
            $endDate->addMinutes($performanceTimeMinutes);

            // 開演時間=終演時間のはずがない
            if ($startDate->diffInMinutes($endDate) >= 0) {
                break;
            }
            $performanceTimeMinutes -= 10;
        } while (0);

        return $performanceTimeMinutes;
    }
}