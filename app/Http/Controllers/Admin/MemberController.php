<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/2/4
 * Time: 15:24
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Models\User;

class MemberController extends CommonController
{

    protected function _delete($id) {
        if (is_array($id)){
            $id = [$id];
        }

        return User::whereIn('id',$id)->delete();
    }
    public function delete() {
        $user = User::find (request('id',0));
        if(empty($user)){
            return $this->error('会员不存在', route('admin.member.index'),'','',false);
        }
        if ($this->_delete (request('id'))) {
            return $this->success ('，会员删除成功', route('admin.member.index'),'','',false );
        }else {
            return $this->error('删除失败', route('admin.member.index'),'','',false);
        }
    }

    public function charge(Request $request) {
        $id = request('id');
        if ($request->isMethod('get')) {
            $user = new User();
            View::share('fields', $info = $user->where('id',$id)->first());
            return view('member.charge');
        }else {
            $userinfo = User::find($id);
            if(empty($userinfo)){
                return $this->error('会员不存在', route('admin.member.index'),'','',true);
            }
            $money = $userinfo['money'] + ((double)$_POST['charge']);
            User::where('id', $id)->update(['money' => $money]);
            // 记录财务
            userFinace($userinfo['id'], $userinfo['email'], $userinfo['mobile'], ((double)$_POST['charge']), $_POST['intro']);
            return $this->success('会员充值成功', route('admin.member.index'),'','',true);
        }

    }

    public function preview(Request $request) {
        $id = request('id');
        if ($request->isMethod('get')) {

            $user = new User();
            View::share('fields', $info = $user->where('id',$id)->first());
            View::share ('member_enabled', $info['member_enabled']);
            View::share ('is_verify',      $info['is_verify']);
            return view('member.preview');
        }else {
            $member_enabled = request('member_enabled');
            $is_verify = request('is_verify');
            User::where('id', $id)->update(['member_enabled' => $_POST['member_enabled'], 'is_verify' => $is_verify]);
            return $this->success('设置会员状态成功', route('admin.member.index'),'','',true);
        }

    }

    public function index() {

        $map = [];
        if (request('search','') == 'go') {
            $keywords = request('keywords','');

        }

        $map['type'] = 'member';
        $member = new User();
        if (!empty($keywords)){
            $member = $member->orWhere('email','like','%' . $keywords . '%')->orWhere('mobile','like','%' . $keywords . '%');
        }
        $list = $member->where( $map )->orderBy('id','desc')->paginate( $this->listrows())->appends($_GET);

        foreach($list as &$account) {
            $account['account_name'] = accountName(null, $account);
        }
        View::share('newest', isset($list[0]) ? $list[0] : '');
        View::share('count', memberCount());
        View::share('today_count',memberDayCount());
        View::share('yesterday_count', memberDayCount(null , null, date('j')-1));
        View::share('list', $list);
        return view('member.index');
    }
}