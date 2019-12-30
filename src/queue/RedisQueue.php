<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/28
 * Time: 18:47
 */
namespace library\queue;

/**
 * redis 队列
 * Class RedisQueue
 * @package library\queue
 */
class RedisQueue extends Queue
{
    /**
     * 队列名称
     * @var string
     */
    static public $prefix = "queue_";
    /**
     * 队列id
     * @var string
     */
    protected $uniqid;
    /**
     * Redis对象
     * @var Redis
     */
    protected $redis;

    public function __construct(int $maxSize = 0, array $option = [])
    {
        parent::__construct($maxSize);
        if( !class_exists("redis") )
        {
            throw new \Exception("Please install 'phpredis' extension", 1);
        }
        //redis配置
        $config = array_merge(["HOST" => "127.0.0.1", "PORT" => 6379, "PASSWORD" => ""], $option);
        //创建Redis对象
        $this->redis = new \Redis();
        //连接
        $this->redis->connect($config['HOST'], $config['PORT']);
        if( $config['PASSWORD'] ) $this->redis->auth( $config['PASSWORD'] );
    }

    /**
     * @param  string  $uniqid 队列id
     * 设置队列id
     */
    public function setUniqid(string $uniqid)
    {
        $this->uniqid = $uniqid;
    }
    /**
     * @return string
     * 获取队列id
     */
    public function getUniqid(): string
    {
        if( !isset( $this->uniqid ) )
        {
            $this->setUniqid( uniqid() );
        }
        return $this->uniqid;
    }
    public function getQueueName(): string
    {
        return static::$prefix . $this->getUniqid();
    }
    /**
     * @return int  队列长度
     */
    public function count()
    {
        return $this->redis->llen( $this->getQueueName() );
    }
    public function enQueue($message): bool
    {
        if( $this->isFull() )
        {
            return false;
        }
        $messageSer = serialize($message);
        $this->redis->rpush($this->getQueueName(), $messageSer);
        return true;
    }
    public function deQueue()
    {
        if( $this->count() )
        {
            $messageSer = $this->redis->lpop( $this->getQueueName() );

            return unserialize($messageSer);
        }
        return false;
    }
}