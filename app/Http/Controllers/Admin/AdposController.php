<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/1/21
 * Time: 10:01
 */

namespace App\Http\Controllers\Admin;

use App\Http\Models\Adpos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AdposController extends CommonController
{

    public function delete(Request $request) {
        $adpos = Adpos::find(request('id'));
        if ($adpos->ads > 0) {
            return $this->error('当前位置下还有广告');
        }

        if ($adpos->delete()) {
            return $this->success($adpos ['name'] . '，删除成功',route('admin.adpos.index'),'','',false);
        }else {
            return $this->error('删除失败',route('admin.adpos.index'),'','',false);
        }
    }



    // 批量操作
    public function doAction() {
        if (isset ( $_POST ['action'] )) {
            $action = input ( 'post.action' );
            if (empty ( $action )) {
                return $this->error ('请选择操作');
            }
            if (empty ( $_POST ['ids'] )) {
                return $this->error ( '请选择要操作的广告位' );
            }
            switch ($action) {
                case 'set-show' :
                    Adpos::where ( 'id', 'in', $_POST ['ids'] )->update ( [
                        'enabled' => 1
                    ] );
                    return $this->success ('设置选中广告位为显示状态成功');
                case 'set-disabled' :
                    Adpos::where ( 'id', 'in', $_POST ['ids'] )->update ( [
                        'enabled' => 0
                    ] );
                    return $this->success ('设置选中广告位隐藏状态成功');
            }
        }
    }

    public function index() {
        $map = [];
        if (request('search','') == 'go') {
            $keywords = request('keywords','');
            if (!empty($keywords)){
                $map[] = ['name','like','%' . $keywords . '%'];
            }
        }
        $adpos = new Adpos();
        $list = $adpos->where ($map)->orderBy ('id','desc')->paginate( $this->listrows())->appends($_GET);
        View::share( 'list', $list );
        return view('adpos.index');
    }

    public function form(Request $request) {
        $id = request('id', 0);
        if ($request->isMethod('get')) {
            if (request('id', 0)) {
                // 编辑页面
                $adpos = Adpos::find ( $id );
                View::share ( 'enabled', $adpos->enabled );
                View::share ( 'fields', $adpos );
            } else {
                // 添加页面
                View::share ( 'enabled', 1 );
            }
        } else {
            $adpos = Adpos::find($id);
            if ($adpos){
                $adpos -> name = $request->input('name');
                $adpos -> type = $request->input('type');
                $adpos -> htmlcode = $request->input('htmlcode');
                $adpos -> intro = $request->input('intro');
                $adpos -> enabled = $request->input('enabled');
                $result = $adpos -> save();

            }else{
                $adpos = new Adpos();
                $result = $adpos->create($request->input());
            }



            if (false === $result) {
                return $this->error ( '新增失败' ,route('admin.adpos.index'),'','',false);
            }
            return $this->success ($adpos['name'].'，保存成功', route('admin.adpos.index'),'','',false);
        }
        return view('adpos.form');
    }
}
