<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/28
 * Time: 18:47
 */
namespace library\queue;

class PhpQueue extends Queue
{
    /**
     * 队列
     * @var SplQueue
     */
    private $queue;

    public function __construct(int $maxSize = 0)
    {
        parent::__construct($maxSize);
        $this->queue = new \SplQueue();
    }
    /**
     * @return int    当前队列长度
     */
    public function count()
    {
        return $this->queue->count();
    }

    public function enQueue($message): bool
    {
        if( $this->isFull() )
        {
            return false;
        }
        $this->queue->enqueue($message);
        return true;
    }

    public function deQueue()
    {
        if( $this->count() )
            return $this->queue->dequeue();
        return false;
    }
}