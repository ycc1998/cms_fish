<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/16
 * Time: 13:47
 */

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Common\Controller\CommonController as Common;
use App\Http\Models\AdminOplog;
use App\Http\Models\ArticleComment;
use App\Http\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

class CommonController extends Common
{
    protected $_is_login  = 0;
    protected $_username  = '';
    protected $_user      = null;             // 后台当前登录帐号
    protected $_message_num   = 0;            // 后台顶部消息数
    protected $_message_array = [];           // 后台顶部消息数组

    public function __construct(Request $request) {

        parent::__construct($request);
        $this->request = $request;

//        View::share('message_num', $this->_message_num);
//        View::share('message_array', $this->_message_array);

//        $this->_username = $request->session()->has('username') ? $request->session()->get('username') : '';
//        View::share('username', $this->_username);
//        $frame_menus = Config::get('menus');
//        $frame_menus = array_map(array($this, 'LeftMenuURL'), $frame_menus);
//        View::share('frame_menus', $frame_menus);
//
//        View::share('mobile_home', $this->siteHome(true));    // 站点演示，移动端
//        View::share('www_home',    $this->siteHome(false));   // 站点演示，PC端
//
//        //$this->loginCheck();
//
//        $cur = strtoupper($this->controller.'/'.$this->actionName);
//        if (!$this->checkPermission($cur)) {
//            return $this->error('您没有权限');
//        }
    }


    public function outmsg($msg,$url=null) {
        Config::set('dispatch_success_tmpl', 'public/cpmsg_out');
       // return parent::success($msg, $url);  // 跳过当前分类的success，不记录操作日志
        return redirect()->route($url);
    }

    protected function listrows() {
        if ($rows = request('rows', '')) {
            return $rows;
        }
        return $this->list_rows;
    }

    protected function getOrder($order = ['id'=>'desc']) {
        // 配置默认排序
        if (!empty($_GET['order'])) {
            $key = urldecode($_GET['order']);
            list($field, $value) = explode('-',  $key);

            if ($value == 'desc' || $value == 'asc') {
                $order = [$field => $value];
            }
        }
        return $order;
    }

    function LeftMenuURL($item) {
        foreach($item['menus'] as &$menus) {
            foreach($menus as &$v) {
                if (is_array($v)) {
                    foreach($v as $_k=> &$_v) {
                        $this->setBadge($_v['newsNum'], $_v);
                        if ($this->checkPermission(strtoupper($_v['url']), true))
                        {
                            $_v['_url'] = $_v['url'];
                            $_v['url']  = url($_v['url']);
                        }else {
                            $_v['url'] = '__NOPERMISSION__';
                        }
                    }
                }
            }
        }
        return $item;
    }

    protected function setBadge(&$num, $menu) {
        $num = 0;
        switch($menu['url']) {
            case 'articleComment/index':
                $articleComment = new ArticleComment();
                $num = $articleComment->where('is_check', '0')->count();
                $this->setMessageNum($num);
                $this->setMessageArray(['url' => route('admin.articleComment.index'), 'message' => $this->messageFormate("未审核文章评论", $num)]);
                break;
            case 'member/index':
                $num = memberDayCount();
                $this->setMessageNum($num);
                $this->setMessageArray(['url' => route('admin.member.index'), 'message' => $this->messageFormate("今日新注册会员", $num)]);
                break;
            case 'MemberFinance/index':
                $num = FinanceDayCount();
                $this->setMessageNum($num);
                $this->setMessageArray(['url' => route('admin.member_finance.index'), 'message' => $this->messageFormate("今日充值会员数", $num)]);
                break;
        }
    }

    protected function messageFormate($message, $num) {
        return sprintf("%s <b>%s</b>", $message, $num);
    }

    // 更新顶部消息数
    protected function setMessageNum($num = 0) {
        $this->_message_num = $this->_message_num + $num;
        View::share('message_num', $this->_message_num);
    }

    // 更新顶部消息数
    protected function setMessageArray($item) {
        array_push($this->_message_array, $item);
        View::share('message_array', $this->_message_array);
    }

    protected function recordLog($res, $message = '') {
        $_permissions = Config::get('permissions');
        $permissions = array_values($_permissions['public']);
        foreach($_permissions['permissions'] as $s) {
            foreach($s as $value) {
                foreach($value['permissions'] as $p=>$n) {
                    array_push($permissions, $p);
                }
            }
        }

        if (!in_array_case($this->controller.'/'.$this->actionName, $permissions)) {
            return;
        }
        $this->_recordLog($res, $message);
    }

    protected function _recordLog($res, $message) {

        $admin = new AdminOplog();
        $type = $this->request->isMethod('get') ? 0 : 1;
        $post_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        // 针对批量操作
        if (strtolower($this->actionName) == 'doaction') {
            if (isset($_POST ['ids'])) {
                $post_id = is_array($_POST ['ids']) ? implode(",", $_POST ['ids']) : $_POST ['ids'];
            }
        }
        $uid = auid();
        $userName = loginAdmin();

        $admin->create(['message' => $message,'uid' => $uid, 'username' => $userName, 'module' => $this->module, 'controller' => $this->controller, 'action' => $this->actionName, 'type' => $type,'result' => $res, 'post_id'=> $post_id, 'ip'=> get_client_ip()]);

        //$admin->save();
    }

    protected function success($msg = '', $url = null, $data = '', $wait = 2, $ajax = true) {
        $this->recordLog(true, $msg);
        //return parent::success($msg, $url, $data, $wait, $header);
        $result = [
            'code' => 1,
            'data' => $data,
            'msg' => $msg,
            'url' => $url,
            'wait' => $wait
        ];

        if ($ajax){
            return response()->json($result);
        }else{
            return response()
                ->view('layouts.tips', $result);
        }

    }

    protected function error($msg = '', $url = null, $data = '', $wait = 2, $ajax = true){
        $this->recordLog(false, $msg);
        $result = [
            'code' => 0,
            'data' => $data,
            'msg' => $msg,
            'url' => $url,
            'wait' => $wait
        ];

        if ($ajax){
            return response()->json($result);
        }else{
            return response()
                ->view('layouts.tips', $result);
        }
    }




    protected function checkPermission($cur, $menu_check = false) {
        $request = $this->request;
        $uid = $request->session()->get('uid');
        if(!empty($uid)) {
            if (is_null($this->_user)) {
                $this->_user = User::find($uid);;
            }
            $user = $this->_user;
            $permissions = $user->getAdminPermissions();
            $configPermissions = Config::get('permissions');
            $permissions = array_merge($configPermissions['public'], $permissions);
            if (!$menu_check) {
                $equal = $configPermissions['equal'];
                foreach($equal as $_cur=>$actions) {
                    if (in_array_case($cur, $actions)) {
                        $cur = $_cur;
                        break;
                    }
                }
            }

            if (!in_array_case($cur, $permissions)) {
                return false; // 这里改成true则都有权限
            }
        }
        return true;
    }

    protected function loginCheck() {

        $request = $this->request;
        if($request->session()->has('is_login')) {
            if ($request->session()->get('is_login') == '1') {
                $this->_is_login = true;
            }else {
                $this->_is_login = false;
            }
        }else {
            $this->_is_login = false;
        }

        if (!$this->_is_login) {
            if (strtolower($this->controller) != 'login') {
                return redirect()->intended(route('admin.login.index'));
            }
        }

        if (strtolower($this->controller) != 'login') {
            $uid = $request->session()->get('uid');
            $session_id = session_id();
            if(!empty($uid)) {
                if (is_null($this->_user)) {
                    $this->_user = User::find($uid);;
                }
                if ( ($this->_user['http_host'] != $_SERVER['HTTP_HOST']) || ($this->_user['session_id'] != $session_id) || ($this->_user['last_loginip'] != get_client_ip())) {
                    $this->_out();
                    return $this->outmsg('您的帐号在别的地方登录', route('admin.login.index'));
                }
            }
        }
    }

    public function _out() {
        $request = $this->request;
        $request->session()->forget('username');
        $request->session()->forget('uid');
        $request->session()->forget('is_login');
    }
}