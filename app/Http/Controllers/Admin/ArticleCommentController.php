<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/2/4
 * Time: 12:18
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

use App\Http\Models\ArticleComment;

class ArticleCommentController extends CommonController
{

    protected function _delete($id) {
        if(!is_array($id)){
            $id = [$id];
        }
        return ArticleComment::whereIn('id',$id)->delete();
    }

    public function delete() {
        $articleComment = ArticleComment::find (request('id',''));
        if (empty($articleComment)){
            return $this->error ( '文章评论不存在', route('admin.articleComment.index'),'','',false);
        }
        if ($this->_delete(request('id',''))) {
            return $this->success ($articleComment ['title'] . '，文章评论删除成功', route('admin.articleComment.index'),'','',false);
        }else {
            return $this->error ( '删除失败', route('admin.articleComment.index'),'','',false);
        }
    }

    // 批量操作
    public function doAction() {
        if (isset ( $_POST ['action'] )) {
            $action = request('action');
            $cid    = request('cid');
            if (empty($action)) {
                return $this->error ( '请选择操作', route('admin.articleComment.index'),'','',true );
            }
            if (empty($_POST ['ids'])) {
                return $this->error ( '请选择要操作的评论' , route('admin.articleComment.index'),'','',true );
            }
            switch ($action) {
                case 'set-delete' :
                    $this->_delete($_POST ['ids']); // 调用统一的删除方法
                    return $this->success ( '选中的文章评论删除成功', route('admin.articleComment.index'),'','',true  );
                case 'set-show' :
                    ArticleComment::whereIn ( 'id', $_POST ['ids'] )->update ( [
                        'is_check' => 1
                    ]);
                    return $this->success ( '设置选中评论为显示状态成功', route('admin.articleComment.index'),'','',true  );
                case 'set-notpass' :
                    ArticleComment::whereIn ( 'id', $_POST ['ids'] )->update ( [
                        'is_check' => -1
                    ]);
                    return $this->success ( '设置选中评论为未通过审核成功' , route('admin.articleComment.index'),'','',true );
                case 'set-disabled' :
                    ArticleComment::whereIn ( 'id', $_POST ['ids'] )->update ( [
                        'is_check' => 0
                    ]);
                    return $this->success ( '设置选中评论隐藏状态成功' , route('admin.articleComment.index'),'','',true );
            }
        }
    }


    public function preview(Request $request) {
        $id = request('id');
        if ($request->isMethod('get')) {
            $comment = new ArticleComment();

            View::share('fields', $comment->where('id', $id)->first());
            return view('articleComment.preview');
        }else {
            $reply = request('reply');
            ArticleComment::where('id', $id)->update(['reply'=>$_POST['reply']]);
            return $this->success('回复成功', route('admin.articleComment.index'),'','',false);
        }
    }

    public function index() {
        $map = [];
        if (request('search','') == 'go') {
            // 查看指定标签的文章
            $type = isset($_GET['type']) ? $_GET['type'] : 'comment';

            $keywords = request('keywords','');
            if (!empty($keywords)){
                $map[] = [$type,'like','%' . $keywords . '%'];
            }
        }

        $filterMap = [];
        if (isset($_GET['is_check'])) {
            switch($_GET['is_check']) {
                case 'yes':
                    $filterMap['is_check'] = '1';
                    break;
                case 'no':
                    $filterMap['is_check'] = '0';
                    break;
                case 'notpass':
                    $filterMap['is_check'] = '-1';
                    break;

            }
        }

        $map = array_merge($map, $filterMap);

        $comment = new ArticleComment();
        $list = $comment->where($map)->orderBy ('id','desc')->paginate( $this->listrows())->appends($_GET);

        View::share( 'list', $list );

        // 筛选菜单组
        $filterMenus = array(
            'is_check' => array(
                'all' => array('url'=>'admin.articleComment.index',  'args' => array(), 'name'=>'全部'),
                'yes' => array('url'=>'admin.articleComment.index',  'args' => array('is_check'=>'yes'), 'name'=>'审核'),
                'notpass' => array('url'=>'admin.articleComment.index',  'args' => array('is_check'=>'notpass'), 'name'=>'未通过' ,'function' => function() {
                    return $this->getNotPassCommentNum('-1');
                }),
                'no'  => array('url'=>'admin.articleComment.index',  'args' => array('is_check'=>'no'),  'name'=>'未审核', 'function' => function(){
                    return $this->getNotPassCommentNum('0');
                }),
            ),
        );

        View::share('filterMenus',$filterMenus);
        return view('articleComment.index');
    }

    // 获取未通过审核评论的数量
    protected function getNotPassCommentNum($map = '-1') {
        $articleComment = new ArticleComment();
        $num = $articleComment->where('is_check', $map)->count();
        return ' ('.$num.')';
    }
}