<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/2/4
 * Time: 19:07
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Http\Models\UserFinance;

class MemberFinanceController extends CommonController
{

    public function index() {
        $map = [];
        if (request('search','') == 'go') {
            $keywords = request('keywords','');
        }

        $log = new UserFinance();

        if (!empty($keywords)){
            $log = $log->orWhere('uid','like','%' . $keywords . '%')
                ->orWhere('email','like','%' . $keywords . '%')
                ->orWhere('mobile','like','%' . $keywords . '%')
                ->orWhere('intro','like','%' . $keywords . '%');
        }

        if (!empty($_GET['start_date_time']) AND !empty($_GET['end_date_time'])) {
            $start_date_time     = strtotime(request('start_date_time'));
            $end_date_time       = strtotime(request('end_date_time'));
            $log = $log->whereBetween('create_time',[$start_date_time, $end_date_time]);
        }

        $list = $log->orderBy('id','desc')->paginate($this->listrows())->appends($_GET);
        View::share('list', $list);
        return view('memberFinance.index');
    }
}