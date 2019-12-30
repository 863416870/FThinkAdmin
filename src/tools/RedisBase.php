<?php
namespace library\tools;

use library\redis\ConnMgr;

/**
 * redis 基类
 * Class Redis
 * @package library\tools
 */
class RedisBase
{
    private $redis;
    public function __construct(array $option = [],
                                $section = 'default') {
        try {
            $this->redis = ConnMgr::getInstance($option, $section);
        } catch (\Exception $ex) {
            throw new \Exception("[Redis Connect Failed] errmsg is [". $ex->getMessage() ."]");
        }

        if($this->redis == false){
            throw new \Exception("[Redis Connect Failed] config is [" . $config . "]");
        }
    }

    public function __call($method, $params) {
        try{
            if (empty($this->redis)) {
                return false;
            } else {
                return call_user_func_array(array($this->redis, $method), $params);
            }
        } catch (\Exception $ex) {
            throw new \Exception("[Redis Operation Failed] errmsg is [". $ex->getMessage() ."]");
        }
        return false;
    }
}