<?php
namespace library\interfaces;

/**
 * 队列接口
 * Class Queue
 * @package library\interfaces
 */
interface Queue extends \Countable
{
    /**
     * @param  int $size 长度
     * 设置队列最大长度
     */
    public function setMaxSize(int $size);

    /**
     * @return int
     * 获取队列最大长度
     */
    public function getMaxSize(): int;
    /**
     * @param  all                 $message 数据
     * @return bool                成/败
     * 入队
     */
    public function enQueue($message): bool;
    /**
     * @return all  队列数据
     * 出队
     */
    public function deQueue();
    /**
     * @return boolean
     * 队列是否已满
     */
    public function isFull(): bool;
}