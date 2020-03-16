<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/1/21
 * Time: 10:01
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Models\AdminRole;
use App\Http\Models\User;

class AdminController extends CommonController
{

    protected function _delete($id) {
        return User::where('id','in',$id)->delete();
    }

    public function delete() {
        $user = User::get(input('id'));
        if ($this->_delete(input('id'))){
            return $this->success($user['username'].'，管理员删除成功',_url('admin/index'));
        }else {
            return $this->error('删除失败');
        }
    }

    public function changePassword() {
        if (request()->isPOST()) {
            $id              = input('post.id');
            $old_password    = input('post.old_password');
            $password        = input('post.password');
            $password_repeat = input('post.password_repeat');

            $user = User::get($id);
            if (md5($old_password) != $user->password) {
                return $this->error('旧的密码不正确');
            }

            if ($password != $password_repeat) {
                return $this->error('两次密码不一致');
            }
            $user->save(['password' => md5($password)]);
            return $this->success($user['username'].'，修改我的密码成功');

        }else {
            $uid = Session::get('uid');
            View::share('uid', $uid);
            $this->view->engine->layout('layout/layer');
            return $this->fetch();
        }
    }

    public function index() {
        $adminRoles = AdminRole::pluck('name','id');
        $user = new User();
        $list = $user->where('type','admin')->orderBy('id','desc')->paginate($this->listrows())->appends($_GET);

        View::share('list', $list);
        View::share('allRoles', $adminRoles);
        return view('admin.index');
    }

    public function form(Request $request)
    {
        $id = request('id','');
        if ($request->isMethod('get')){
            $adminRoles = AdminRole::all();
            if (request('id','')) {
                // 编辑页面
                $user = User::find($id);
                $admin_enabled = $user->admin_enabled;
                View::share('admin_enabled', $admin_enabled);
                View::share('fields', $user);
                View::share('admin_roles', is_array(json_decode($user['admin_roles'])) ? json_decode($user['admin_roles']) : []);

            }else {
                // 添加页面
                View::share('admin_enabled', 1);
                View::share('admin_roles', []);
            }

            View::share('adminRoles', $adminRoles);
        }else {
            $_POST['admin_roles'] = json_encode(isset($_POST['admin_roles']) ? $_POST['admin_roles'] : []);

            $data = ['username' => $_POST['username'], 'password' => md5($_POST['password']), 'admin_enabled' => intval($_POST['admin_enabled']), 'type' => 'admin', 'admin_roles'=>$_POST['admin_roles']];
            if ($_POST['password'] != $_POST['password_repeat']) {
                return $this->error('两次密码必须一致',route('admin.admin.index'),'','',false);
            }

            if (!empty($_POST['password']) and request('passwordStrength') == '-1') {
                return $this->error('密码太简单了',route('admin.admin.index'),'','',false);
            }

            if (request('id','')) {
                $user = User::find($id);
                // 如果没有输入新的密码，则使用旧的密码
                if (empty($_POST['password']) and empty($_POST['password_repeat'])) {
                    $data['password'] = $user['password'];
                }

                $user->update($data);
            }else {
                // MD5后，空字符串也有数据了
                if (empty($_POST['password']) || empty($_POST['username'])) {
                    return $this->error('请输入用户名或用户密码',route('admin.admin.index'),'','',false);
                }
                $user = new User();
                $result = $user->create($data);
                if(false === $result){
                    return $this->error('用户创建失败',route('admin.admin.index'),'','',false);
                }
            }
            return $this->success($user['username'].'，管理员保存成功',route('admin.admin.index'),'','',false);
        }
        return view('admin.form');
    }
}