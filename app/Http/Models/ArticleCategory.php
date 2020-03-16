<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/16
 * Time: 10:41
 */

namespace App\Http\Models;


class ArticleCategory extends Common
{
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //设置表名
    protected $table = 'article_category';

    protected $fillable = [
        'name', 'pid', 'position','intro','create_time','update_time',
    ];

    public function getTreeChilds($pid = 0) {
        $childs = array();
        $tree = get_tree($this->select(), $pid);
        foreach($tree as $_tree) {
            array_push($childs, $_tree['id']);
        }
        return $childs;
    }

    // 当前分类ID和子分类ID不能作为父级分类ID
    public function pidVerify($pid) {
        $verifyPids = array($pid);
        return array_merge($this->getTreeChilds($pid), $verifyPids);
    }

    // $id 传入分类ID，判断是否还有子类，一般用于删除判断
    public function has_child($id) {
        return $this->where(array('pid'=>$id))->count();
    }

    public function getArticlesAttribute()
    {
        return (new Article())->where('cid', $this->id)->count();
    }

    public function categoryLevelLimit($pid, $level = 3) {
        $level--;
        $tree = get_tree($this->where([])->select());
        foreach($tree as $category) {
            if ($pid == $category['id'] AND $category['level'] >= $level) {
                return false;
            }
        }
        return true;
    }
}