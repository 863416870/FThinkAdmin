<?php
namespace library\interfaces;

/**
 * swoole 并发类
 * Interface Many
 * @package library\interfaces
 */
interface Many
{
    /**
     * @param  int                    $cnum 并发数
     * 设置并发数
     */
    public function setCnum(int $cnum);
    /**
     * @return int               并发数
     * 获取并发数
     */
    public function getCnum(): int;
    /**
     * @param  callable               $go  并发函数
     * @param  array                 $par 并发参数
     * @return void
     * 设置并发可执行结构
     */
    public function go(callable $go, ...$par);
    /**
     * @param  callable               $to 收尾函数
     * @return void
     * 设置收尾可执行结构
     */
    public function to(callable $to);
    /**
     * @return callable                 可执行结构
     * 获取可并发可执行结构
     */
    public function getGo(): callable;
    /**
     * @return callable                 可执行结构
     * 获取收尾可执行结构
     */
    public function getTo(): callable;
    /**
     * @return array                 并发函数参数
     * 获取参数
     */
    public function getPar(): array;
    /**
     * @return void
     * 开始
     */
    public function run();
}