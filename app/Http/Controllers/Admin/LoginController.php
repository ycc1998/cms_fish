<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/17
 * Time: 11:10
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Models\AdminLoginlog;
use App\Http\Models\AdminRole;
use App\Http\Models\User;
use App\Http\validate\Login;
use Illuminate\Support\Facades\DB;

class LoginController extends CommonController
{

    public function index(){
        return view('admin.pc.login.index');
    }

    public function check(Login $request) {

        $data = $request->all();


        $username = $data['username'];
        $password = md5($data['password']);

        $admin = new User();
        $row   = $admin->where([
            ['username',$username],
            ['password',$password],
            ['type','admin']
        ])->first();

        if(!empty($row)) {
            if ($row['admin_enabled'] != '1') {
                return redirect('login/index')
                    ->withErrors('暂时无法登录！')
                    ->withInput();
            }
            $row = $row->toArray();
            $session_id = session_id();
            $admin = User::find($row['id']);
            $admin -> session_id = $session_id;
            $admin -> last_login = time();
            $admin -> last_loginip = get_client_ip();
            $admin -> http_host = $_SERVER['HTTP_HOST'];
            $admin->save();

            DB::table('user')->where('id', $row['id'])->increment('login_count');

            session(['username' => $row['username']]);
            session(['uid' => $row['id']]);
            session(['is_login' => '1']);

            $roles = !(empty($row['admin_roles'])) ? json_decode($row['admin_roles']) : [];
            $_roles = [];
            $adminRoles = AdminRole::pluck('name','id');

            foreach($adminRoles as $key=>$value) {
                if (in_array($key , $roles)) {
                    $_roles[] = $value;
                }
            }
            $role = implode("，", $_roles);
            $row = array_merge($row, ['_role'=>$role]);
            $this->recodeLoginLog($row,1);
            return redirect()->route('admin.index');
        }else {
            $this->recodeLoginLog($row,0);
            return redirect()->route('admin.login.index')
                ->withErrors('用户名或者密码错误！')
                ->withInput();
        }
    }

    protected function recodeLoginLog($info , $res) {
        $log = ['uid'=> isset($info['id']) ? $info['id'] : 0, 'username' => isset($info['username']) ? $info['username'] : htmlentities($_POST['username'],ENT_QUOTES ) , 'result'=> $res , 'role' => isset($info['_role']) ? $info['_role'] : '', 'ip' => get_client_ip(), 'login_time' => time()];
        AdminLoginlog::create($log);
    }

    public function out() {
        $this->_out();
        return redirect()->route('admin.login.index');
    }
}