# php
 ####通用网络请求
 ````
 // 发起get请求
 $result = http_get($url,$query,$options);
 $result = \library\tools\Http::get($url,$query,$options);
 
 // 发起post请求
 $result = http_post($url,$data,$options);
 $result = \library\tools\Http::post($url,$data,$options);     
 ````
 
 ####emoji 表情转义（部分数据库不支持可以用这个）
 ````
 // 输入数据库前转义
 $content = emoji_encode($content);
 
 // 输出数据库后转义
 $content = emoji_decode($content);      
 ````  
 ####获取对象反射实例
  ````
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