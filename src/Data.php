<?php

namespace jinyicheng\toolbox;
class Data
{
    /**
     * 字符串截取，支持中文和其他编码
     * @static
     * @access public
     * @param string $str 需要转换的字符串
     * @param int $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param bool $suffix 截断显示字符
     * @return string
     */
    static public function subString($str, $start, $length, $charset = "utf-8", $suffix = true)
    {
        if (function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
        } else {
            $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }
        return $suffix ? $slice . '...' : $slice;
    }

    /**
     * 数组转xml
     * @param $array
     * @return string
     */
    public static function arrayToXml($array)
    {
        $xml = "<xml>";
        foreach ($array as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * xml转数组
     * @param $xml
     * @return mixed
     */
    public static function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

    /**
     * 检查字符串是否是UTF8编码
     * @param string $string 字符串
     * @return Boolean
     */
    static public function isUtf8($string)
    {
        $len = strlen($string);
        for ($i = 0; $i < $len; $i++) {
            $c = ord($string[$i]);
            if ($c > 128) {
                if (($c >= 254)) return false;
                elseif ($c >= 252) $bits = 6;
                elseif ($c >= 248) $bits = 5;
                elseif ($c >= 240) $bits = 4;
                elseif ($c >= 224) $bits = 3;
                elseif ($c >= 192) $bits = 2;
                else return false;
                if (($i + $bits) > $len) return false;
                while ($bits > 1) {
                    $i++;
                    $b = ord($string[$i]);
                    if ($b < 128 || $b > 191) return false;
                    $bits--;
                }
            }
        }
        return true;
    }
}