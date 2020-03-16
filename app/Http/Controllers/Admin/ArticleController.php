<?php


namespace App\Http\Controllers\Admin;

use App\Http\Models\ArticleCategory;
use App\Http\Models\ArticleTags;
use Illuminate\Support\Facades\View;
use App\Http\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends CommonController
{
    use \App\Http\Controllers\Traits\Tags;

    protected function _delete($id) {
        if (!is_array($id)){
            $id = [$id];
        }
        return Article::whereIn('id',$id)->delete();
    }

    public function delete() {
        $article = Article::find ( request('id','') );
        if (!$article){
            return $this->error('文章不存在！', route('admin.article.index'),'','',false);
        }
        if ($this->_delete ( request('id') )) {
            return $this->success ( $article->title . '，文章删除成功', route('admin.article.index'),'','',false);
        }else {
            return $this->error('删除失败', route('admin.article.index'),'','',false);
        }
    }

    // 批量操作
    public function doAction() {
        if (isset ( $_POST ['action'] )) {
            $action =  request('action');
            $cid = request('cid');
            if (empty ( $action )) {
                return $this->error ( '请选择操作', route('admin.article.index'),'','',true );
            }
            if (empty ( $_POST ['ids'] )) {
                return $this->error ( '请选择要操作的文章', route('admin.article.index'),'','',true );
            }
            switch ($action) {
                case 'set-delete' :
                    $this->_delete ( $_POST ['ids'] ); // 调用统一的删除方法
                    return $this->success ( '选中的文章删除成功' , route('admin.article.index'),'','',true);
                case 'set-cid' :
                    if (empty ( $cid )) {
                        return $this->error ( '请选择目标分类', route('admin.article.index'),'','',true );
                    }
                    Article::whereIn ( 'id', $_POST ['ids'] )->update ( [
                        'cid' => $cid
                    ] );
                    return $this->success ( '修改选中的文章分类成功' , route('admin.article.index'),'','',true);
                case 'set-show' :
                    Article::whereIn ( 'id', $_POST ['ids'] )->update ( [
                        'enabled' => 1
                    ] );
                    return $this->success ( '设置选中文章为显示成功', route('admin.article.index'),'','',true );
                case 'set-disabled' :
                    Article::whereIn ( 'id', $_POST ['ids'] )->update ( [
                        'enabled' => 0
                    ] );
                    return $this->success ( '设置选中文章隐藏成功', route('admin.article.index'),'','',true );
            }
        }
    }

    public function index() {
        $map = [];
        if (request('search','') == 'go') {
            $keywords = request('keywords','');
            if (!empty($keywords)){
                $map[] = ['title','like','%' . $keywords . '%'];
            }

            $cid = request('cid','');
            if ($cid != 0) {
                $map = array_merge($map, ['cid'=>$cid]);
            }
        }



        $filterMap = [];
        if (isset($_GET['recommend'])) {
            switch($_GET['recommend']) {
                case 'hot':
                    $filterMap['is_hot'] = '1';
                    break;
                case 'recommend':
                    $filterMap['is_recommend'] = '1';
                    break;
                case 'top':
                    $filterMap['is_top'] = '1';
                    break;
            }
        }
        if (isset($_GET['state'])) {
            switch($_GET['state']) {
                case 'hidden':
                    $filterMap['enabled'] = '0';
                    break;
                case 'show':
                    $filterMap['enabled'] = '1';
                    break;
            }
        }

        $map = array_merge($map, $filterMap);


        $article = new Article();
        if (!empty(request('ids'))) {
            $article = $article->whereIn('id',explode(',',urldecode($_GET['ids'])));
        }
        $list = $article->where($map)->orderBy ('id','desc')->paginate( $this->listrows())->appends($_GET);

        View::share ( 'list', $list );
        $articleCategory = new ArticleCategory();
        $tree = get_tree ( $articleCategory->orderBy('position','desc')->get());
        View::share( 'tree', $tree );
        View::share ( 'categorys', $articleCategory->pluck ( 'name', 'id' ) );

        // 筛选菜单组
        $filterMenus = array(
            'state' => array(
                'all'    => array('url'=>'admin.article.index',  'args' => array(), 'name'=>'全部文章'),
                'show'   => array('url'=>'admin.article.index',  'args' => array('state'=>'show'), 'name'=>'显示'),
                'hidden' => array('url'=>'admin.article.index',  'args' => array('state'=>'hidden'), 'name'=>'隐藏'),
            ),
            'recommend' => array(
                'recommend'   => array('url'=>'admin.article.index',  'args' => array('recommend'=>'recommend'), 'name'=>'推荐'),
                // 联动检索的实现方式
                //'recommend'   => array('url'=>'article/index',  'args' => array('state'=>'show', 'recommend'=>'recommend'), 'name'=>'推荐'),
                'hot'         => array('url'=>'admin.article.index',  'args' => array('recommend'=>'hot'), 'name'=>'热门'),
                'top'         => array('url'=>'admin.article.index',  'args' => array('recommend'=>'top'), 'name'=>'置顶'),
            ),
        );

        View::share('filterMenus',$filterMenus);
        return view('article.index');
    }
    public function form(Request $request) {
        $id = request('id','');
        $articleCategory = new ArticleCategory ();
        $tree = get_tree ( $articleCategory->where ([])->orderBy('position','desc')->get());

        if ($request->isMethod('get')) {
            if (request('id','')) {
                // 编辑页面
                $article = Article::find ( $id );
                $enabled = $article->enabled;
                View::share ( 'enabled', $enabled );
                View::share ( 'fields', $article );
            } else {
                // 添加页面
                View::share ( 'enabled', 1 );
            }
            View::share ( 'tree', $tree );
        } else {

            if (!uploadFilesCheck('album')) {
                $this->error('图片超出数量', route('admin.article.index'),'','',true);
            }

            if ((new ArticleCategory())->has_child($_POST['cid'])) {
                $this->error('请添加到最后一级分类中', route('admin.article.index'),'','',true);
            }

            $_POST ['is_recommend'] = isset ( $_POST ['is_recommend'] ) ? intval ( $_POST ['is_recommend'] ) : 0;
            $_POST ['is_hot'] = isset ( $_POST ['is_hot'] ) ? intval ( $_POST ['is_hot'] ) : 0;
            $_POST ['is_top'] = isset ( $_POST ['is_top'] ) ? intval ( $_POST ['is_top'] ) : 0;
            $_POST ['date_time'] = strtotime ( $_POST ['date_time'] );
            $_POST['uid'] = $request->session()->get('uid');
            $tags = $_POST['tags'] = $this->_trim_tags($_POST['tags']);

            // 检查标签关键字是否正确
            if ($error = $this->_checktags($tags)) {
                return $this->error($error, route('admin.article.index'),'','',true);
            }

            // 处理POST请求
            if (request('id','')) {
                // 保存
                $article = Article::find ( $id );
                $oldtags = request('oldtags');
                $data = $_POST;
                unset($data['_token'],$data['oldtags'],$data['multi_num'],$data['uid']);


                $result = $article->where('id',$id)->update ($data);
                if (false === $result) {
                    return $this->error ( '文章保存失败！', route('admin.article.index'),'','',true);
                }
                $this->updatetags(request('id'), $tags, $oldtags);
                return $this->success ( $article['title'].'，保存文章成功', route('admin.article.index'),'','',true);
            } else {
                // 添加
                $_POST ['click_num'] = intval($_POST['click_num']) > 0 ? intval($_POST['click_num']) : 1; // 阅读次数没有为0的情况
                $article = new Article ();
                $data = $_POST;

                unset($data['_token'],$data['oldtags'],$data['multi_num']);
                $result = $article->create ($data);

                if (false === $result) {
                    return $this->error ( '文章保存失败！', route('admin.article.index'),'','',true);
                }
                if ($tags) {
                    $this->_insert_tags(new ArticleTags(), $tags, $result['id']);
                }
                return $this->success ( $article['title'].'，文章添加成功', route('admin.article.index'),'','',true );
            }
        }
        return view('article.form');
    }

    protected function updatetags($id, $newtags, $oldtags) {
        $this->_updatetags(new ArticleTags(), $id, $newtags, $oldtags );
    }

    public function tags() {
        $tags = $this->_tags(new ArticleTags());
        View::share('tags', $tags);
        return view('article.tags');
    }
}
