<?php


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Models\ArticleCategory;

class ArticleCategoryController extends CommonController
{

    public function doAction() {
        if (isset($_POST['do']) and isset($_POST['position'])) {
            $articleCategory = new ArticleCategory;
            foreach ($_POST['position'] as $key => $value) {
                $articleCategory->where(['id' => $key])->update(['position' => $value]);
            }
            return $this->success('更新排序成功', route('admin.articleCategory.index'),'','',false);
        }
    }

    // 当前分页数量
    public function delete() {
        $articleCategory = ArticleCategory::find(request('id',0));
        if ($articleCategory and $articleCategory->has_child(request('id',0))) {
            return $this->error('当前分类下还有子分类！', route('admin.articleCategory.index'),'','',false);
        }
        if ($articleCategory->articles > 0) {
            return $this->error('当前分类下还有文章！', route('admin.articleCategory.index'),'','',false);
        }
        if ($articleCategory) {
            $articleCategory->delete();
            return $this->success($articleCategory['name'].'，文章分类删除成功', route('admin.articleCategory.index'),'','',false);
        }else {
            return $this->error('删除失败', route('admin.articleCategory.index'),'','',false);
        }
    }


    public function index() {
        $articleCategory = new ArticleCategory();
        $list = $articleCategory->orderBy('position','desc')->orderBy('id','desc')->get();
        $tree = get_tree($list);
        View::share('list', $tree);
        return view('articleCategory.index');
    }

    public function form(Request $request)
    {
        $id = request('id','');
        $articleCategory = new ArticleCategory();
        $tree = get_tree($articleCategory->where([])->orderBy('id','desc')->orderBy('position','desc')->get());
        if ($request->isMethod('get')){
            if (request('id','')) {
                // 编辑页面
                View::share('fields', ArticleCategory::find($id));
            }else {
                // 添加页面
                View::share('fields', ['position'=>0]);
            }
            View::share('tree', $tree);
        }else {
            unset($_POST['_token']);
            if (request('id','')) {
                $articleCategory = ArticleCategory::find($id);
                if (!$articleCategory->categoryLevelLimit($_POST['pid'])) {
                    return $this->error('文章分类最多三级', route('admin.articleCategory.index'),'','',false);
                }
                $childs = $articleCategory->pidVerify( $id );
                if (in_array ( $_POST ['pid'], $childs )) {
                    return $this->error ('不能将父级分类设置为现有的子类（或当前类）', route('admin.articleCategory.index'),'','',false);
                }

                $result = $articleCategory->where('id',request('id',''))->update ( $_POST );
            }else {
                $articleCategory = new ArticleCategory();
                if (!$articleCategory->categoryLevelLimit($_POST['pid'])) {
                    return $this->error('文章分类最多三级', route('admin.articleCategory.index'),'','',false);
                }

                $result = $articleCategory->create ( $_POST );
            }

            if(false === $result){
                return $this->error('保存失败', route('admin.articleCategory.index'),'','',false);
            }
            return $this->success('文章分类保存成功', route('admin.articleCategory.index'),'','',false);
        }
        return view('articleCategory.form');
    }
}
