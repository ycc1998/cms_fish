<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/1/21
 * Time: 18:34
 */

namespace App\Http\Controllers\Admin;


use App\Http\Models\AdminLoginlog;
use Illuminate\Support\Facades\View;

class LoginlogController extends CommonController
{
    protected function scope_search() {
        $map = [];
        if (!empty($_GET['start_date_time']) AND !empty($_GET['end_date_time'])) {
            $start_date_time     = strtotime(input('get.start_date_time'));
            $end_date_time       = strtotime(input('get.end_date_time'));
            $map['login_time']   = array('BETWEEN', array($start_date_time, $end_date_time));
        }
        return $map;
    }

    public function index() {
        $map = [];
        $log = new AdminLoginlog();
        if (request('search','') == 'go') {
            $keywords = request('keywords');
            if (!empty($keywords)){
                $map[] = ['username','like','%' . $keywords . '%'];
            }
            if (!empty($_GET['start_date_time']) AND !empty($_GET['end_date_time'])) {
                $start_date_time     = strtotime(request('start_date_time',''));
                $end_date_time       = strtotime(request('end_date_time',''));

                if (!empty($end_date_time)){
                    $log = $log-> whereBetween('login_time',[$start_date_time, $end_date_time]);
                }
            }
        }


        $list = $log->where($map)->orderBy('id','desc')->paginate($this->listrows())->appends($_GET);
        View::share('list', $list);

        return view('loginlog.index');
    }

    public function activeLoginLog() {
        $this->view->engine->layout('layout/layer');
        $map = ['uid'=>auid()];
        if (input('get.search') == 'go') {
            $map = array_merge($map, $this->scope_search());
        }

        $log = new AdminLoginlog();
        $list = $log->where($map)->order($this->getOrder())->paginate(10)->appends($_GET);
        $this->assign('list', $list);
        return $this->fetch();
    }
}