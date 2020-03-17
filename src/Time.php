<?php

namespace jinyicheng\toolbox;
class Time
{
    /**
     * 获取毫秒时间戳
     * @return int
     */
    public static function microTimestamp()
    {
        $time = explode(" ", microtime());
        $time = $time[1] . ($time[0] * 1000);
        $time = explode(".", $time);
        return (int)$time[0];
    }

    /**
     * 现在到明天0点的秒数
     * @return int
     */
    public static function nowTillTomorrowMorningLeftSenconds()
    {
        return strtotime('+1 day', strtotime(date('Y-m-d'))) - time();
    }

    /**
     * 获取几秒前, 几小时前, 几天前 等等
     * 例子：Time::getHowLongAgo(strtotime('2019-3-18'))
     * @param $timestamp
     * @return string
     */
    public static function getHowLongAgo($timestamp)
    {
        $t = time() - $timestamp;
        $f = [
            '31536000' => '年',
            '2592000' => '个月',
            '604800' => '星期',
            '86400' => '天',
            '3600' => '小时',
            '60' => '分钟',
            '1' => '秒'
        ];
        $result = '';
        foreach ($f as $k => $v) {
            if (0 != $c = floor($t / (int)$k)) {
                $result = $c . $v . '前';
                break;
            }
        }
        return $result;
    }
}