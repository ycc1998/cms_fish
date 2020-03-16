<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/2/4
 * Time: 18:28
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Http\Models\UserLoginlog;

class MemberLoginlogController extends CommonController
{

    public function index() {
        $map = [];
        if (request('search') == 'go') {
            $keywords = request('keywords');
            if (!empty($keywords)){
                $map[]  = ['account','like','%' . $keywords . '%'];
            }


            if (!empty($_GET['start_date_time']) AND !empty($_GET['end_date_time'])) {
                $start_date_time     = strtotime(request('start_date_time'));
                $end_date_time       = strtotime(request('end_date_time'));
                $map['login_time']   = array('BETWEEN', array($start_date_time, $end_date_time));
            }
        }
        $log = new UserLoginlog();
        $list = $log->where($map)->orderBy('id','desc')->paginate($this->listrows())->appends($_GET);
        View::share('list', $list);
        return view('memberLoginLog.index');
    }
}