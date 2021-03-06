**git**

````
git tag -a v0.21 -m "publish v0.21 version"
git push origin v0.21

git tag -d v0.21
git push origin :refs/tags/v0.21
````
# php
**通用网络请求**

 ````
 // 发起get请求
 $result = http_get($url,$query,$options);
 $result = \library\tools\Http::get($url,$query,$options);
 
 // 发起post请求
 $result = http_post($url,$data,$options);
 $result = \library\tools\Http::post($url,$data,$options);     
 ````

 **emoji 表情转义（部分数据库不支持可以用这个）**
 ````
 // 输入数据库前转义
 $content = emoji_encode($content);
 
 // 输出数据库后转义
 $content = emoji_decode($content);      
 ````
**获取对象反射实例**

  ````php
  获取类反射实例
  $reflex = Reflex($object);
  获取类方法反射示例
  $reflex = Reflex($object);
  $actionReflex = $reflex->setMethod($action);
  
  类注释举例：
  /**
   * Class Book
   * @route('v1/book')
   * @package app\api\controller\v1
   */
  class Book
  {
  }
  获取：
  $route = $reflex->get('route',['rule']);
  结果：
  $route = {
      ['rule' => '/v1/book/']
  }
  
  
  方法注释举例：
  /**
   * 查询指定bid的图书
   * @route('v1/book/:bid','get')
   * @param Request $bid
   * @param('bid','bid的图书','require')
   * @return mixed
   */
  public function getBook($bid)
  {
      $result = BookModel::get($bid);
      return $result;
  }
  获取：
  $route = $actionReflex->get('route',['rule','method']);
  结果：
  $route = {
      ['rule' => '/v1/book/','method' => 'get']
  }
  
  ````

**队列**

```php
use library\queue;

$queue = new queue(100);
$queue->setUniqid('1');
var_dump($queue->getMaxSize());
var_dump($queue->isFull());
var_dump($queue->count());
var_dump($queue->getQueueName());
while (count($queue)) {
	var_dump( $queue->deQueue() );
}
/*
$i = 0;
while( !$queue->isFull() ) { 
	var_dump( $queue->enQueue($i++) );
}
*/
```

**并发类制定 swoole协程并发类**

```php
$many = new many\SwooleMany(4);
$queue = new queue\PhpQueue(10);
$a = 0;
while ( $queue->isFull() ) {
	$queue->enQueue( ++$a );
}
$many->go(function($ser,$que){
	print_r( $ser->getMid() );
	$list = [];
	while ( count($que) ) {
		$list[] = $que->deQueue();
	}
	return $list;
	
},$queue);
$many->to(function($ser, $list){
	print_r( $ser->getSid() );
	print_r( $list );
});
```

**Redis**

```php
use library\tools\RedisBase;

$redisBase = new RedisBase($option,$section = 'default');
$option参数
 ["host" => "127.0.0.1",
"port" => "6379",
"connect_timeout" => "1.5",
"connect_retry_delay" => '100', //毫秒
"connect_retry_times" => 3,
"password" => '',]
```

