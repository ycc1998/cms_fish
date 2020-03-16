<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/2/4
 * Time: 11:18
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Http\Models\ArticleTags;

class ArticleTagsController extends CommonController
{

    protected function _delete($id) {
        if (!is_array($id)){
            $id = [$id];
        }
        return ArticleTags::whereIn('id',$id)->delete();
    }

    public function delete() {
        $articleTags = ArticleTags::find (request('id',''));
        if ($this->_delete ( request('id','') )) {
            return $this->success ( $articleTags ['tag_name'] . '，文章标签删除成功', route('admin.articleTags.index'),'','',false );
        }else {
            return $this->error('删除失败', route('admin.articleTags.index'),'','',false);
        }
    }

    public function index() {
        $map = [];
        if (request('search','') == 'go') {
            $keywords = request('keywords','');
            if (!empty($keywords)){
                $map[] = ['tag_name','like','%' . $keywords . '%'];
            }
        }
        $articleTags = new ArticleTags ();
        $list = $articleTags->where ( $map )->orderBy ('id','desc')->paginate( $this->listrows())->appends($_GET);
        View::share ( 'list', $list );
        return view('articleTags.index');
    }
}