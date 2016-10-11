<?php
namespace Hanauta\HpEventCalendar\Official;

use phpQuery;

/**
 * 公式サイトのニュースページをアレコレしたい
 * Class News
 * @package Hanauta\HpEventCalendar\Official
 */
class News extends Official
{
    /**
     * 一覧ページから取得する
     * @param  string    $url
     * @param null $domain
     * @return array
     */
    public function scrapingList($url,$domain=null)
    {
        $doc = phpQuery::newDocumentFile($domain.$url);

        $links = [];
        foreach ($doc['#news_contents']->find('li') as $entry) {

            // ユニット名
            $units = pq($entry)->attr('class');
            $units = explode(' ',trim($units));

            // タイプ
            $type = pq($entry)->find('.icon-schedule')->attr('class');
            $type = explode(' ',trim($type));

            // 詳細ページURL
            $link = pq($entry)->find('p')->find('a')->attr('href');

            // イベント名
            $title = pq($entry)->find('p')->find('a')->text();

            $links[] = [
                'type' => $type[1],
                'title' => $title,
                'url' => $domain.$link,
                'units' => $units,
            ];
        }

        return $links;
    }

    /**
     * 詳細ページから取得する
     * @param array $stage
     * @return array
     */
    function scrapingNewsDetail($stage)
    {
        // HTMLの取得
        $doc = phpQuery::newDocumentFile($stage['url']);

        // ヘッダー取得
        $header = [];
        foreach ($doc['#concert_schedule']->find('tr')->find('th') as $key => $item){
            $val = $this->replaceHeader(pq($item)->text());
            if(!empty($val)){
                $header[$key] = $val;
            }
        }

        $events = [];
        foreach ($doc['#concert_schedule']->find('tr') as $index => $rows) {
            $val = [];
            foreach ($header as $key => $item){
                $val[$item] = pq($rows)->find('td:eq('.$key.')')->text();
            }
            if(empty($val[self::HEADER_DAY])){
                continue;
            }
            $val[self::HEADER_DAY] = $this->formatColumnDay($val[self::HEADER_DAY]);
            if(!empty($val[self::HEADER_OPEN_TIME])) {
                $openDate = $this->formatColumnDate($val[self::HEADER_DAY],$val[self::HEADER_OPEN_TIME]);
                $val[self::HEADER_OPEN_DATE] = $openDate;
            }
            if(!empty($val[self::HEADER_START_TIME])) {
                $startDate = $this->formatColumnDate($val[self::HEADER_DAY],$val[self::HEADER_START_TIME]);
                $val[self::HEADER_START_DATE] = $startDate;
            }
            if (!empty($val[self::HEADER_PLACE])) {
                $place = $this->formatColumnPlace($val[self::HEADER_PLACE]);
                $val[self::HEADER_PLACE] = $place;
            }

            $events[$val[self::HEADER_DAY]][] = $val;
        }
        return $events;
    }
}