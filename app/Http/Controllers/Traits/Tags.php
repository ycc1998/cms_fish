<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/16
 * Time: 11:17
 */

namespace App\Http\Controllers\Traits;


trait Tags
{
    protected function _trim_tags($s) {
        $s = preg_replace('/\s+/', '', $s);
        $s = str_replace('，', ',', $s);
        $s = preg_replace('#,+#', ',', $s);
        $s = trim($s, ' ,');
        return $s;
    }

    protected function _checktags($tags, $num = 5) {
        $v = explode(',', $tags);
        $v_num = count($v);
        $result = '';
        if ($v_num > $num) {
            $result .= '标签(Tags)的关键字不能超过'.$num.'个';
            return $result;
        } else {
            for($i=0; $i<$v_num; $i++) {
                if(strlen($v[$i]) > 15) {
                    $result .= '标签(Tags)的每个关键字不能超过15个字符, '.$v[$i].' 超过了15个字符';
                    return $result;
                }
            }
        }
    }

    protected function _tags($model, $num = 20) {
        $model->orderBy('id', 'desc');
        if ($num > 0){
            $model->limit($num);
        }
        return $model->get();
    }

    public function tagIds($model, $id) {
        $ids = $model->where('id', $id)->first();
        return $ids['tag_ids'];
    }

    protected function _updatetags($table, $id, $newtags, $oldtags) {
        if (substr($newtags, -1) == ',') {
            $newtags = substr($newtags, 0, strlen($newtags)-1);
        }
        $arrtag     = explode(',', $newtags);
        $arrold     = explode(',', $oldtags);
        $arrtag_num = count($arrtag);
        $arrold_num = count($arrold);

        // 增加新的标签
        for($i=0; $i < $arrtag_num; $i++) {
            if (empty($arrtag[$i]) ) {
                continue;
            }
            if (!in_array($arrtag[$i], $arrold)) { // 新增的标签不在旧的里面，那么分两种情况
                $arrtag[$i] = trim($arrtag[$i]);
                if ($arrtag[$i]) {
                    $tag = $table->where('tag_name', $arrtag[$i])->first();
                    if(empty($tag)) { // 新的标签以前不存在
                        $table->create(['tag_name'=>$arrtag[$i],'tag_usenum'=>1, 'tag_ids'=>$id]);
                    } else { // 新的标签以前存在，则更新引用次数和引用文档
                        if (!empty($tag['tag_ids'])) {
                            $ids = $tag['tag_ids'].','.$id;
                        }else {
                            $ids = $id;
                        }
                        // 更新status值为1 并且id大于10的数据
                        $table->where('tag_name', $arrtag[$i])->update(['tag_ids' => $ids]);
                        $table->where(array('tag_name'=>$arrtag[$i]))->increment('tag_usenum',1);
                    }
                }
            }
        }

        // 减少标签的情况
        for($i=0; $i<$arrold_num; $i++) {
            if (empty($arrold[$i]) ) {
                continue;
            }
            if (!in_array($arrold[$i], $arrtag)) { // 旧的标签不在新的标签内，则需要减小引用次数
                $tag = $table->where('tag_name', $arrold[$i])->first();
                $tag['tag_ids'] = str_replace(','.$id, '', $tag['tag_ids']);
                $tag['tag_ids'] = str_replace($id.',', '', $tag['tag_ids']);
                $tag['tag_ids'] = str_replace($id, '', $tag['tag_ids']); // 这三行清除当前的引用

                $table->where(array('tag_name'=>$arrold[$i]))->decrement('tag_usenum');
                $table->where('tag_name', $arrold[$i])->update(['tag_ids' => $tag['tag_ids']]);
            }
        }
    }

    protected function _insert_tags($table, $tags, $last_id) {
        $tagdb  = explode(',', $tags);
        $tagnum = count($tagdb);
        for($i=0; $i < $tagnum; $i++) {
            $tagdb[$i] = trim($tagdb[$i]);
            if ($tagdb[$i]) {

                $tag = $table->where('tag_name', $tagdb[$i])->first();
                if(!$tag) { // 新增
                    $table->create(['tag_name'=>$tagdb[$i],'tag_usenum'=>1,'tag_ids'=>$last_id]);

                } else {
                    $ids = $tag['tag_ids'].','.$last_id;
                    $table->where('tag_name', $tagdb[$i])->update(['tag_ids' => $ids]);
                    $table->where(array('tag_name'=>$tagdb[$i]))->increment('tag_usenum',1);
                }
            }
        }
    }
}