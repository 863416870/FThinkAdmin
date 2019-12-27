<?php
namespace library\redis;
/**
 * Redis_ConnMgr
 * redis 连接处理
 */
class ConnMgr
{
    /**
     * 单例 
     */
    private static $instances = array();

    /**
     * __construct 
     * 构造函数，不做任何事情 
     * 
     * @return void
     */
    private function __construct()
    {
    }


    /**
     * getInstance 
     *
     * 获取redis实例
     * 
     * @param string $section 
     * 
     * @return void
     */
    public static function getInstance($section = 'default')
    {
        if (isset(self::$instances[$section])) {
            return self::$instances[$section];
        }

        $conn = self::getConnect($section);
        if ($conn == false) {
            return false;
        }

        self::$instances[$section] = $conn;
        return self::$instances[$section];
    }

    //连接redis
    private static function getConnect($section){
        $configArr = self::getConfig($section);
        if(empty($configArr)){
            return false; 
        }

        $redis = new \Redis();

        $retryTimes = isset($configArr['connect_retry_times']) ? intval($configArr['connect_retry_times']) : 1;
        $retryDelay = isset($configArr['connect_retry_delay']) ? intval($configArr['connect_retry_delay']) : 100;
        $timeout = isset($configArr['connect_timeout']) ? $configArr['connect_timeout'] : 1;

        $password = isset($configArr['password']) ? $configArr['password'] : "";

        $ret = $redis->connect($configArr['host'], $configArr['port'], $timeout, null, $retryDelay);

        if($ret){
            if($password){
                $redis->auth($password);
            }

            return $redis; 
        }

        return false;

    }

    //获取redis连接配置
    private static function getConfig($section){
        $confMap = array(
            //默认业务redis，线上线下隔离
            "default" => array(
                "host" => "47.93.119.95",
                "port" => "6379",
                "connect_timeout" => "1.5",
                "connect_retry_delay" => '100', //毫秒
                "connect_retry_times" => 3,
                "password" => '8a42f6a4f0656076bacaa4771179f7d6',
            ),
            "new" => array(
                "host" => "127.0.0.1",
                "port" => "6379",
                "connect_timeout" => "1.5",
                "connect_retry_delay" => '100', //毫秒
                "connect_retry_times" => 3,
                "password" => '8a42f6a4f0656076bacaa4771179f7d6',
            ),
        );

        if(!isset($confMap[$section])){
            return array();
        }

        return $confMap[$section];
    }
}
