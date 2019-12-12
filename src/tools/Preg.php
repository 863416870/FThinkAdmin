<?php

namespace library\tools;

/**
 * Preg 正则工具
 * Class Preg
 * @package library\tools
 */
class Preg
{
    //是否是合法金额
    public static function IsFee($Argv){
        $RegExp = '/^(([1-9]\d{0,9})|0)(\.\d{1,2})?$/';
        return preg_match($RegExp,$Argv) ? true : false;
    }

    public static function IsAlnum($Argv){
        $RegExp='/^[a-zA-Z0-9]+$/'; //由大小写字母跟数字组成
        return preg_match($RegExp,$Argv) ? true : false;
    }

    public static function IsAlpha($Argv){
        $RegExp='/^[A-Za-z]+$/'; //是否是26个英文字母组成
        return preg_match($RegExp,$Argv) ? true : false;
    }

    public static function IsLower($Argv){
        $RegExp='/^[a-z]+$/'; //是否是26个英文小写字母组成
        return preg_match($RegExp,$Argv) ? true : false;
    }

    public static function IsAlnumunder($Argv){
        $RegExp = '/^[0-9A-Za-z_]+$/';//是否是数字字母下划线组成
        return preg_match($RegExp,$Argv) ? true : false;
    }

    public static function IsUpperLetter($Argv){
        $RegExp='/^[A-Z]$/'; //是否是大写字母
        return preg_match($RegExp,$Argv) ? true : false;
    }

    public static function IsDigits($Argv){
        $RegExp = '/^-?[0-9]\d*$/';
        return preg_match($RegExp,$Argv) ? true : false;
    }


    //是否合法的业务ID，如audit_user_id goods_id sku_id等等
    public static function IsUID($argv){
        $regExp = '/^[1-9]\d{0,19}$/';
        return preg_match($regExp, $argv) ? true : false;
    }

    /**
     * IsMail函数:检测是否为正确的邮件格式
     * 返回值:是正确的邮件格式返回邮件,不是返回false
     */
    public static function IsEmail($Argv){
        $RegExp='/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i';
        return preg_match($RegExp,$Argv) ? true : false;
    }

    /**
     * IsQQ函数:检测参数的值是否符合QQ号码的格式
     * 返回值:是正确的QQ号码返回QQ号码,不是返回false
     */
    public static function IsQQ($Argv){
        $RegExp='/^[1-9][0-9]{5,12}$/';
        return preg_match($RegExp,$Argv) ? true : false;
    }

    /**
     * IsPhone函数:检测参数的值是否为正确的中国手机号码格式
     * 返回值:是正确的手机号码返回手机号码,不是返回false
     */
    public static function IsPhone($Argv){
        $RegExp='/^1\d{10}$/';
        return preg_match($RegExp,$Argv) ? true : false;
    }

    /**
     * IsTel函数:检测参数的值是否为正取的中国电话号码格式包括区号
     * 返回值:是正确的电话号码返回电话号码,不是返回false
     */
    public static function IsTel($Argv){
        $RegExp='/[0-9]{3,4}-[0-9]{7,8}$/';
        return preg_match($RegExp,$Argv) ? true : false;
    }

    /**
     * IsNickname函数:检测参数的值是否为正确的昵称格式(Beta)
     * 返回值:是正确的昵称格式返回昵称格式,不是返回false
     */
    public static function IsNickname($Argv){
        $RegExp='/^\s*$|^c:\\con\\con$|[%,\*\&quot;\s\t\&lt;\&gt;\&amp;\'\(\)]|\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8/is'; //Copy From DZ
        return preg_match($RegExp,$Argv) ? true : false;
    }

    /**
     * IsChinese函数:检测参数是否为中文
     * 返回值:是返回参数,不是返回false
     */
    public static function IsChinese($Argv,$Encoding='utf8'){
        $RegExp = $Encoding=='utf8'?'/^[\x{4e00}-\x{9fa5}] $/u':'/^([\x80-\xFF][\x80-\xFF]) $/';
        return preg_match($RegExp,$Argv) ? true : false;
    }

    /**
     * IsChineseOrAlnum函数:检测参数是否为中文字母数字 （utf8或gb2312）
     * 返回值:是返回参数,不是返回false
     */
    public static function IsChineseOrAlnum($Argv,$Encoding='utf8') {
        $RegExp = ($Encoding=='utf8'?'/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u':'/^[".chr(0xa1)."-".chr(0xff)."A-Za-z0-9_]+$/');
        return preg_match($RegExp,$Argv) ? true : false;
    }

    /**
     * IsCommaNum函数:检测是否为用逗号隔开的数字或单个数字
     * 返回值:是返回true,不是返回false
     */
    public static function IsCommaNum($Argv){
        $RegExp='/^(\d+[,])*(\d+)$/';
        return preg_match($RegExp,$Argv) ? true : false;
    }

    public static function LengthOver($string,$len) {
        $length = Util::LengthOfUTF8($string);
        return $length > $len;
    }

    public static function LengthRange($string, $min, $max){
        $length = Util::LengthOfUTF8($string);
        return ($length >= $min && $length <= $max);
    }

    public static function IsIllegal($Argv){
        $RegExp = '/\#|\%|\@|\$|\+|\=|\)|\(|\<|\>|\{|\}|\.|\'|\]|\[|\/|\\\|\"|\"/';
        return preg_match($RegExp, $Argv)?$Argv:false;
    }

    //是否为URL
    public static function IsUrl($Argv){
        $RegExp = '/^(?:(?:https?|ftp):\/\/)?(?:\S+(?::\S*)?@)?(?:(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/';
        return preg_match($RegExp, $Argv)?$Argv:false;
    }
    //判断日期是否合法
    public static function IsDate($Argv, $dateFormat = "Y-m-d"){
        return (date($dateFormat, strtotime($Argv)) == $Argv) ? $Argv : false;
    }
}
