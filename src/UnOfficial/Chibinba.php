<?php
namespace Hanauta\HpEventCalendar\UnOfficial;

use phpQuery;

class Chibinba
{
    const DOMAIN_HP = 'http://www.chibinba.com/cgi/webcal';

    // ヘッダーキー
    const HEADER_DAY        = 'day';
    const HEADER_START_TIME = 'start_time';
    const HEADER_START_DATE = 'start_date';
    const HEADER_PLACE      = 'place';
    const HEADER_EVENT      = 'event';


    /**
     * 一覧ページから取得する
     *
     * @param  string $url
     * @param null $domain
     * @return array
     */
    public function scrapingList($url, $domain = null)
    {
        mb_language('japanese');
        $html = file_get_contents($domain . $url);
        $doc = phpQuery::newDocument(mb_convert_encoding($html, 'UTF-8', 'auto'));

        // ヘッダー取得
        $header = [];
        foreach ($doc['body > center > table']->find('tr')->find('th') as $key => $item) {
            var_dump(pq($item)->text());
            $val = $this->replaceHeader(pq($item)->text());
            if (!empty($val)) {
                $header[$key] = $val;
            }
        }

        $events = [];
        foreach ($doc['body > center > table']->find('tr') as $index => $rows) {
            $val = [];
            foreach ($header as $key => $item) {
                $val[$item] = pq($rows)->find('td:eq(' . $key . ')')->text();
            }
            if (empty($val[self::HEADER_DAY])) {
                continue;
            }

            var_dump($val);
        }

    }


    /**
     * ヘッダーをキーとして取得
     *
     * @param string $val
     * @return string
     */
    protected function replaceHeader($val)
    {
        switch ($val) {
            case '月日':
                return self::HEADER_DAY;
            case '場所':
                return self::HEADER_PLACE;
            case '時間':
                return self::HEADER_START_TIME;
            case 'タイトル':
                return self::HEADER_EVENT;
            default :
                return '';
        }
    }
}
