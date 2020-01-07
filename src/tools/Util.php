<?php

namespace library\tools;

class Util
{
    //将数字转为圆角分
    //如：10009 -> 100.09 5000 -> 50.00
    public static function numberToFee($number){
        if(empty($number)){
            return 0;
        }
        $yuanFee = $number / 100;
        $ret = number_format($yuanFee, 2, ".", "");
        return $ret;
    }

    //数字转圆角分，并去除小数点后多余的0
    //如：10009 -> 100.09 5000 -> 50
    public static function trimZero($number){
        return floatval(self::numberToFee($number));
    }

    //价格比较
    public static function priceEqual($priceOne, $priceTwo){
        $ret_one = (string)$priceOne;
        $ret_two = (string)$priceTwo;
        if($ret_one == $ret_two){
            return true;
        }else{
            return false;
        }
    }

    //获取指定长度随机字符串
    public static function getRandString($length = 4){
        $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPRSTUVWXYZ23456789'; //随机字符
        $randCode = "";
        for ( $i = 0; $i < $length; $i++ ){
            $randCode .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $randCode;
    }

    //获取指定长度随机数字字符串
    public static function getNumberRandString($length = 4){
        $chars = '0123456789'; //随机字符
        $randCode = "";
        for ( $i = 0; $i < $length; $i++ ){
            $randCode .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $randCode;
    }


    //获取指定长度随机字符串,小写字母
    public static function getLowerRandString($length = 4){
        $randCode = self::getRandString($length);
        return strtolower($randCode);
    }



    //获得utf8字符字符串真正长度，主要针对汉字
    public static function LengthOfUTF8($string){
        if (empty($string)) {
            return 0;
        }
        // 将字符串分解为单元
        preg_match_all("/./us", $string, $match);
        // 返回单元个数
        return count($match[0]);
    }

    //过滤特殊字符串
    public static function filter($string){
        $string = strip_tags($string);
        $string = htmlspecialchars($string);
        return $string;
    }


    //过滤空格
    public static function trimString($str){
        return preg_replace("/s|　/", "", $str);
    }

    //提取汉字、数字、字母、下划线
    public static function filterNormalChar($str){
        $flag = preg_match_all('/[\x{4e00}-\x{9fa5}a-zA-Z0-9_\.]/u', $str, $matches);
        $rs = "";
        if($flag){
            $rs = implode("", $matches[0]);
        }

        return $rs;
    }

    //去除微信昵称特殊字符
    public static function filterNickedName($str){
        $flag = preg_match_all('/([\x{2E80}-\x{9FFF}a-zA-Z0-9]+)/u', $str, $matches);
        $rs = "";
        if($flag){
            $rs = implode("", $matches[0]);
        }
        return $rs;
    }
}