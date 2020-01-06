<?php


namespace library\helper;

use library\Helper;
use think\db\Query;

/**
 * 表单视图管理器
 * Class FormHelper
 * @package library\helper
 */
class FormHelper extends Helper
{
    /**
     * 表单额外更新条件
     * @var array
     */
    protected $where;

    /**
     * 数据对象主键名称
     * @var string
     */
    protected $field;

    /**
     * 数据对象主键值
     * @var string
     */
    protected $value;

    /**
     * 模板数据
     * @var array
     */
    protected $data;

    /**
     * 逻辑器初始化
     * @param string|Query $dbQuery
     * @param string $field 指定数据主键
     * @param array $where 额外更新条件
     * @param array $data 表单扩展数据
     * @return array|boolean
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function init($dbQuery, $field = '', $where = [], $data = [])
    {
        $this->query = $this->buildQuery($dbQuery);
        list($this->where, $this->data) = [$where, $data];
        $this->field = empty($field) ? ($this->query->getPk() ? $this->query->getPk() : 'id') : $field;;
        $this->value = input($this->field, isset($data[$this->field]) ? $data[$this->field] : null);
        // GET请求, 获取数据并显示表单页面
        if ($this->app->request->isGet()) {
            if ($this->value !== null) {
                $where = [$this->field => $this->value];
                $data = (array)$this->query->where($where)->where($this->where)->find();
            }
            $data = array_merge($data, $this->data);
            if (false !== $this->controller->callback('_form_filter', $data)) {
                return $this->controller->success('恭喜, 数据获取成功!', $data);
            }
            return $this->controller->success('恭喜, 数据获取成功!', $data);
        }
        // POST请求, 数据自动存库处理
        if ($this->app->request->isPost()) {
            $data = array_merge($this->app->request->post(), $this->data);
            if (false !== $this->controller->callback('_form_filter', $data, $this->where)) {
                $result = data_save($this->query, $data, $this->field, $this->where);
                if (false !== $this->controller->callback('_form_result', $result, $data)) {
                    if ($result !== false) $this->controller->success('恭喜, 数据保存成功!', '');
                    $this->controller->error('数据保存失败, 请稍候再试!');
                }
                return $result;
            }
        }
    }
}