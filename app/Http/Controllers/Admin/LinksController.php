<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/1/18
 * Time: 22:36
 */

namespace App\Http\Controllers\Admin;


use App\Http\Models\Links;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class LinksController extends CommonController
{

    protected function _delete($id) {
        if (!is_array($id)){
            $id = [$id];
        }
        return Links::whereIn('id',$id)->delete();
    }

    public function delete(Request $request) {
        $link = Links::find(request('id'));
        if ($this->_delete(request('id'))) {
            return $this->success($link ['name'] . '，删除友情链接成功',route('admin.links.index'),'','',false);
        }else {
            return $this->error('删除失败',route('admin.links.index'),'','',false);
        }
    }

    // 批量操作
    public function doAction() {
        if (isset ( $_POST ['action'] )) {
            $action = request('action');
            if (empty ( $action )) {
                return $this->error ('请选择操作',route('admin.links.index'),'','',false);
            }
            if ($action == 'set-position') {
                if (isset($_POST['position'])) {
                    $links = new Links;
                    foreach ($_POST['position'] as $key => $value) {
                        $links->where('id',$key)->update(['position' => $value]);
//                        $links->save(['position' => $value], ['id' => $key]);
                    }
                }
                return $this->success('设置友情链接排序成功',route('admin.links.index'));
            }
            if (empty ( $_POST ['ids'] )) {
                return $this->error ( '请选择要操作的友情链接' ,route('admin.links.index'));
            }
            switch ($action) {
                case 'set-delete' :
                    $this->_delete ( $_POST ['ids'] ); // 调用统一的删除方法
                    return $this->success ('选中的友情链接删除成功',route('admin.links.index'));
                case 'set-show' :
                    Links::whereIn ( 'id', $_POST ['ids'] )->update ( [
                        'enabled' => 1
                    ] );
                    return $this->success ('设置选中友情链接为显示状态成功',route('admin.links.index'));
                case 'set-disabled' :
                    Links::whereIn ( 'id', $_POST ['ids'] )->update ( [
                        'enabled' => 0
                    ] );
                    return $this->success ('设置选中友情链接隐藏状态成功',route('admin.links.index'));
            }
        }
    }

    public function index() {
        $map = [];
        $map2 = [];
        if (request('search', '') == 'go') {
            $keywords = request('keywords', '');

            $map[] = ['name','like','%' . $keywords . '%'];
            $map2[] = ['url','like','%' . $keywords . '%'];
        }
        $link = new Links();
        $list = $link
            ->where($map)
            ->orWhere($map2)
            ->orderBy('position','desc','id','desc')
            ->paginate($this->listrows())->appends($_GET);
        View::share( 'list', $list );
        return view('links.index');
    }

    public function form(Request $request) {
        $id = request('id', 0);
        if ($request->isMethod('get')) {
            if (request('id', 0)) {
                // 编辑页面
                $link = Links::find ( $id );
                View::share( 'enabled', $link->enabled );
                View::share ( 'fields', $link );
            } else {
                // 添加页面
                View::share  ( 'enabled', 1 );
            }
        } else {
            $_POST['class_key'] = strtoupper($request->input('class_key'));

            if (empty($_POST['name']) || empty($_POST['url'])){
                return $this->error ( '缺少参数' );
            }

            $link = Links::find($id);
            if ($link){
                $link -> name = $request->input('name');
                $link -> class_key = $request->input('class_key');
                $link -> url = $request->input('url');
                $link -> enabled = $request->input('enabled');
                $link -> position = $request->input('position');
                $result = $link -> save();

            }else{
                $link = new Links();
                $result = $link->create($request->input());
            }

            if (false === $result) {
                return $this->error ( '新增失败' ,route('admin.links.index'),'','',false);
            }
            return $this->success ($link['name'].'，友情链接保存成功', route('admin.links.index'),'','',false);
        }
        return view('links.form');
    }
}