<?php
namespace library\queue;

/**
 * 队列抽象类
 * Class Queue
 * @package library\queue
 */
abstract class Queue implements \library\interfaces\Queue
{
    /**
     * 默认队列长度
     */
    const DEFUALT_MAX_SIZE = 1000000;
    /**
     * 队列长度
     * @var int
     */
    private $maxSize;
    /**

     * @param  int $maxSize 队列长度
     * 构造函数
     */
    public function __construct(int $maxSize = 0)
    {
        $this->setMaxSize($maxSize);
    }

    public function setMaxSize(int $size)
    {
        if(!$size || $size > static::DEFUALT_MAX_SIZE)
        {
            $size = static::DEFUALT_MAX_SIZE;
        }
        $this->maxSize = $size;
    }

    public function getMaxSize(): int
    {
        return $this->maxSize;
    }

    public function isFull(): bool
    {
        return $this->count() >= $this->getMaxSize();
    }
}