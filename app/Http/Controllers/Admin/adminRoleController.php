<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/1/21
 * Time: 14:38
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Models\AdminRole;

class adminRoleController extends CommonController
{

    protected function _delete($id) {
        if (!is_array($id)){
            $id = [$id];
        }
        return AdminRole::whereIn('id',$id)->delete();
    }

    public function delete() {
        $adminRole = AdminRole::find ( request('id','') );
        if ($this->_delete ( request('id','') )) {
            return $this->success ( $adminRole ['name'] . '，管理员角色删除成功', route('admin.adminRole.index'),'','',false );
        }else {
            return $this->error('删除失败', route('admin.adminRole.index'),'','',false);
        }
    }

    public function index() {

        $adminRole = new AdminRole();
        $list = $adminRole->orderBy ('id','desc')->paginate ( $this->listrows())->appends($_GET);
        View::share( 'list', $list );
        return view('adminRole.index');
    }

    public function form(Request $request) {

        $id = request('id','');
        if ($request->isMethod('get')) {
            $permissions = config('permissions');
            if (request('id','')) {
                // 编辑页面
                $adminRole = AdminRole::find ( $id );
                $enabled = $adminRole->enabled;
                View::share( 'enabled', $enabled );

                if (!empty($adminRole['permissions'])) {
                    $adminRole['permissions'] = json_decode($adminRole['permissions']);
                }else {
                    $adminRole['permissions'] = [];
                }
                View::share( 'fields', $adminRole );

            } else {
                // 添加页面
                View::share( 'enabled', 1 );
            }
            View::share('permissions', $permissions);

        } else {
            $_POST['permissions'] = json_encode(isset($_POST['permissions']) ? $_POST['permissions'] : array());

            $isUpdate = false;
            $adminRole = new AdminRole ();
            unset($_POST['_token']);
            if (request('id','')) {
                $result = $adminRole->where('id',request('id',''))->update ( $_POST );
            }else{
                $result = $adminRole->create ( $_POST );
            }

            if (false === $result) {
                return $this->error ( '操作失败',route('admin.adminRole.index'),'','',false );
            }

            return $this->success ( $adminRole['name']. '，管理员角色保存成功', route('admin.adminRole.index'),'','',false);
        }
        return view('adminRole.form');
    }
}