<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/1/21
 * Time: 17:00
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Http\Models\AdminOplog;

class OplogController extends CommonController
{

    public function index() {
        $map = [];
        $log = new AdminOplog();
        if (request('search','') == 'go') {
            $keywords = request('keywords');
            if (!empty($keywords)){
                $map[] = ['username','like','%' . $keywords . '%'];
            }
            if (!empty($_GET['start_date_time']) AND !empty($_GET['end_date_time'])) {
                $start_date_time     = strtotime(request('start_date_time',''));
                $end_date_time       = strtotime(request('end_date_time',''));

                if (!empty($end_date_time)){
                    $log = $log-> whereBetween('create_time',[$start_date_time, $end_date_time]);
                }
            }
        }



        $list = $log->where($map)->orderBy('id','desc')->paginate($this->listrows())->appends($_GET);

        $actions = array_case_upper_recursion(Config('actions'), CASE_UPPER);
        View::share('list', $list);
        View::share('actions', $actions);
        return view('oplog.index');
    }
}