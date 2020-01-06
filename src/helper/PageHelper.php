<?php

namespace library\helper;

use library\Helper;
use think\Db;
use think\db\Query;

/**
 *
 * Class PageHelper
 * @package library\helper
 */
class PageHelper extends Helper
{
    /**
     * 是否启用分页
     * @var boolean
     */
    protected $page;

    /**
     * 集合分页记录数
     * @var integer
     */
    protected $total;

    /**
     * 集合每页记录数
     * @var integer
     */
    protected $limit;


    /**
     * 逻辑器初始化
     * @param string|Query $dbQuery
     * @param boolean $page 是否启用分页
     * @param boolean $total 集合分页记录数
     * @param integer $limit 集合每页记录数
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function init($dbQuery, $page = true, $total = false, $limit = 0)
    {
        $this->page = $page;
        $this->total = $total;
        $this->limit = $limit;
        $this->query = $this->buildQuery($dbQuery);
        // 列表排序操作
        if ($this->controller->request->isPost()) $this->_sort();
        // 未配置 order 规则时自动按 sort 字段排序
        if (!$this->query->getOptions('order') && method_exists($this->query, 'getTableFields')) {
            if (in_array('sort', $this->query->getTableFields())) $this->query->order('sort desc');
        }
        // 列表分页及结果集处理
        if ($this->page) {
            // 分页每页显示记录数
            $limit = intval($this->controller->request->get('limit', cookie('page-limit')));
            cookie('page-limit', $limit = $limit >= 10 ? $limit : 20);
            if ($this->limit > 0) $limit = $this->limit;
            $page = $this->query->paginate($limit, $total, ['query' => ($query = $this->controller->request->get())]);
            $result = ['page' => ['limit' => intval($limit), 'total' => intval($page->total()), 'pages' => intval($page->lastPage()), 'current' => intval($page->currentPage())], 'list' => $page->items()];
        } else {
            $result = ['list' => $this->query->select()];
        }
        if (false !== $this->controller->callback('_page_filter', $result['list'])) {
            return $this->controller->success('分页成功, 正在刷新页面！', $result);
        }
        return $this->controller->success('分页成功, 正在刷新页面！', $result);
    }

    /**
     * 列表排序操作
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    protected function _sort()
    {
        switch (strtolower($this->controller->request->post('action', ''))) {
            case 'resort':
                foreach ($this->controller->request->post() as $key => $value) {
                    if (preg_match('/^_\d{1,}$/', $key) && preg_match('/^\d{1,}$/', $value)) {
                        list($where, $update) = [['id' => trim($key, '_')], ['sort' => $value]];
                        if (false === Db::table($this->query->getTable())->where($where)->update($update)) {
                            return $this->controller->error('排序失败, 请稍候再试！');
                        }
                    }
                }
                return $this->controller->success('排序成功, 正在刷新页面！', '');
            case 'sort':
                $where = $this->controller->request->post();
                $sort = intval($this->controller->request->post('sort'));
                unset($where['action'], $where['sort']);
                if (Db::table($this->query->getTable())->where($where)->update(['sort' => $sort]) !== false) {
                    return $this->controller->success('排序参数修改成功！', '');
                }
                return $this->controller->error('排序参数修改失败，请稍候再试！');
        }
    }

}