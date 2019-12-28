<?php


namespace library\tools;

use think\Db;
use think\db\Query;

/**
 * Class SelectTree
 * @package library\tools
 */
class SelectTree
{
    public $arr = array();
    public $icon = array('│','├─',' └─');
    /**
     * @access private
     */
    public $ret = '';
    public $arrTree = array();
    public $option = array(
        /* 主键 */
        'primary_key'   => 'id',
        /* 菜单 */
        'menu_name'     => 'name',
        /* 父键 */
        'parent_key'    => 'parentid'
    );
    /**
     * 构造函数，初始化类
     * @param array 2维数组，例如：
     * array(
     *      1  => array('id' =>'1','parentid' =>0,'name' =>'一级栏目一'),
     *      2  => array('id' =>'2','parentid' =>0,'name' =>'一级栏目二'),
     *      3  => array('id' =>'3','parentid' =>1,'name' =>'二级栏目一'),
     *      4  => array('id' =>'4','parentid' =>1,'name' =>'二级栏目二'),
     *      5  => array('id' =>'5','parentid' =>2,'name' =>'二级栏目三'),
     *      6  => array('id' =>'6','parentid' =>3,'name' =>'三级栏目一'),
     *      7  => array('id' =>'7','parentid' =>3,'name' =>'三级栏目二')
     *      )
     *
     *  $st = new SelectTree($arr);
     *   dump($st->getArray());
     * array(7) {
    [1] => array(3) {
    ["id"] => string(1) "1"
    ["parentid"] => int(0)
    ["name"] => string(16) " 一级栏目一"
    }
    [3] => array(3) {
    ["id"] => string(1) "3"
    ["parentid"] => int(1)
    ["name"] => string(46) "&nbsp;&nbsp;&nbsp;&nbsp;├─ 二级栏目一"
    }
    [6] => array(3) {
    ["id"] => string(1) "6"
    ["parentid"] => int(3)
    ["name"] => string(73) "&nbsp;&nbsp;&nbsp;&nbsp;│&nbsp;&nbsp;&nbsp;&nbsp;├─ 三级栏目一"
    }
    [7] => array(3) {
    ["id"] => string(1) "7"
    ["parentid"] => int(3)
    ["name"] => string(74) "&nbsp;&nbsp;&nbsp;&nbsp;│&nbsp;&nbsp;&nbsp;&nbsp; └─ 三级栏目二"
    }
    [4] => array(3) {
    ["id"] => string(1) "4"
    ["parentid"] => int(1)
    ["name"] => string(47) "&nbsp;&nbsp;&nbsp;&nbsp; └─ 二级栏目二"
    }
    [2] => array(3) {
    ["id"] => string(1) "2"
    ["parentid"] => int(0)
    ["name"] => string(16) " 一级栏目二"
    }
    [5] => array(3) {
    ["id"] => string(1) "5"
    ["parentid"] => int(2)
    ["name"] => string(47) "&nbsp;&nbsp;&nbsp;&nbsp; └─ 二级栏目三"
    }
    }
     */
    function __construct($arr = array(), $option = array())
    {
        $this->option = array_merge($this->option, $option);
        $this->arr =  $arr;
    }
    /**
     * 得到父级数组
     * @param int
     * @return array
     */
    function get_parent($myid)
    {
        $newarr = array();
        if(!isset($this->arr[$myid])) return false;
        extract($this->option);
        $pid = $this->arr[$myid][$parent_key];
        $pid = $this->arr[$pid][$parent_key];
        if(is_array($this->arr))
        {
            foreach($this->arr as $id => $a)
            {
                if($a[$parent_key]  == $pid) $newarr[$id]  = $a;
            }
        }
        return $newarr;
    }
    /**
     * 得到子级数组
     * @param int
     * @return array
     */
    function get_child($myid)
    {
        extract($this->option);
        $newarr = array();
        if(is_array($this->arr) || is_object($this->arr))
        {
            foreach($this->arr as $id => $a)
            {
                if($a[$parent_key] == $myid) $newarr[$id] = $a;
            }
        }
        return $newarr ? $newarr : false;
    }
    /**
     * 得到当前位置数组
     * @param int
     * @return array
     */
    function get_pos($myid,&$newarr)
    {
        $a = array();
        if(!isset($this->arr[$myid])) return false;
        extract($this->option);
        $newarr[]  = $this->arr[$myid];
        $pid  = $this->arr[$myid][$parent_key];
        if(isset($this->arr[$pid]))
        {
            $this->get_pos($pid,$newarr);
        }
        if(is_array($newarr))
        {
            krsort($newarr);
            foreach($newarr as $v)
            {
                $a[$v['id']]  = $v;
            }
        }
        return $a;
    }

    /**
     * 格式化数组
     */
    function getArray($myid = 0, $sid = 0, $fill='&nbsp;', $adds = '')
    {
        $number = 1;
        $child = $this->get_child($myid);
        extract($this->option);
        if(is_array($child)) {
            $total = count($child);
            foreach($child as $id => $a) {
                $j = $k = '';
                if($number == $total) {
                    $j .= $this->icon[2];
                } else{
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';
                @extract($a);
                $a[$menu_name] = $spacer . ' ' . $a[$menu_name];
                $this->arrTree[$a['id']] = $a;
                $fill_rep = str_repeat($fill, 4);
                $fd = $adds . $k . $fill_rep;
                $this->getArray($id, $sid, $fill, $fd);
                $number++;
            }
        }
        return $this->arrTree;
    }
}
