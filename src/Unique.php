<?php

namespace jinyicheng\toolbox;
class Unique
{
    /**
     * 生成唯一token
     */
    static public function token()
    {
        return md5(self::id() . self::sn());
    }

    /**
     * 生成UUID 单机使用
     * @access public
     * @return string
     */
    static public function id()
    {
        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $char_id = md5(uniqid(mt_rand(), true));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            . substr($char_id, 0, 8) . $hyphen
            . substr($char_id, 8, 4) . $hyphen
            . substr($char_id, 12, 4) . $hyphen
            . substr($char_id, 16, 4) . $hyphen
            . substr($char_id, 20, 12)
            . chr(125);// "}"
        return $uuid;
    }

    /**
     * 生成序列号
     * @param string $perfix_str
     * @return string
     */
    public static function sn($perfix_str = '')
    {
        //订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
        $order_id_main = date('YmdHis') . rand(10000000, 99999999);

        //订单号码主体长度
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;
        for ($i = 0; $i < $order_id_len; $i++) {
            $order_id_sum += (int)(substr($order_id_main, $i, 1));
        }

        //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
        return $perfix_str . $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);
    }
}