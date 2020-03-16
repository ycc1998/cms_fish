<?php

namespace App\Http\Middleware;

use App\Http\Models\ArticleComment;
use App\Http\Models\Settings;
use App\Http\Models\User;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class LoginCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public $request;
    protected $_user      = null;
    protected $module         = '';
    protected $controller     = '';
    protected $actionName     = '';
    protected $_username  = '';
    protected $_message_num   = 0;            // 后台顶部消息数
    protected $_message_array = [];           // 后台顶部消息数组

    public function handle($request, Closure $next)
    {
        $this->request = $request;
        $this->initialization();
        if($request->session()->has('is_login')) {
            if ($request->session()->get('is_login') == '1') {
                $_is_login = true;
            }else {
                $_is_login = false;
            }
        }else {
            $_is_login = false;
        }

        if (!$_is_login) {
            return redirect()->intended(route('admin.login.index'));
        }
        $this->load();
        return $next($request);
    }

    public function load()
    {
        $request = $this->request;

        View::share('message_num', $this->_message_num);
        View::share('message_array', $this->_message_array);

        $this->_username = $request->session()->has('username') ? $request->session()->get('username') : '';
        View::share('username', $this->_username);

        $frame_menus = Config::get('menus');
        $frame_menus = array_map(array($this, 'LeftMenuURL'), $frame_menus);

        View::share('frame_menus', $frame_menus);

        View::share('mobile_home', $this->siteHome(true));    // 站点演示，移动端
        View::share('www_home',    $this->siteHome(false));   // 站点演示，PC端


        $controllers = []; // 顶部大模块和具体控制器对应关系数组
        foreach ($frame_menus as $k => $menu) {
            foreach ($menu['menus'] as $_menus) {
                foreach ($_menus as $_items) {
                    if (is_array($_items)) {
                        foreach($_items as $_k => $_item) {
                            if ($_item['url'] != '__NOPERMISSION__'){
                                $_controllerName = getControllerNameByUrl($_item['_url']);
                                $controllers[$k][] = strtolower($_controllerName);
                            }
                        }
                    }else {
                        continue;
                    }
                }
            }
        }
        View::share('controllers',$controllers);
        //$this->loginCheck();

        $cur = strtoupper($this->controller.'/'.$this->actionName);
        if (!$this->checkPermission($cur)) {
            $result = [
                'code' => 0,
                'data' => '',
                'msg' => '您没有权限',
                'url' => route('admin.index'),
                'wait' => 2
            ];
            //dd(1);
            echo response()
                ->view('layouts.tips', $result);die;
        }

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
                            $_v['url']  = url('admin/'.$_v['url']);
                        }else {
                            $_v['url'] = '__NOPERMISSION__';
                        }
                    }
                }
            }
        }

        return $item;
    }

    protected function checkPermission($cur, $menu_check = false) {
        $request = $this->request;
        $uid = $request->session()->get('uid');
        if(!empty($uid)) {
            if (is_null($this->_user)) {
                $this->_user = User::find($uid);
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
                $this->setMessageArray(['url' => route('admin.memberFinance.index'), 'message' => $this->messageFormate("今日充值会员数", $num)]);
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

    public function initialization()
    {
        $request = $this->request;
        list($class, $method) = explode('@', $request->route()->getActionName());
        # 模块名
        $this->module = str_replace(
            '\\',
            '.',
            str_replace(
                'App\\Http\\Controllers\\',
                '',
                trim(
                    implode('\\', array_slice(explode('\\', $class), 0, -1)),
                    '\\'
                )
            )
        );
        # 控制器名称
        $this->controller = str_replace(
            'Controller',
            '',
            substr(strrchr($class, '\\'), 1)
        );
        # 方法名
        $this->actionName = $method;

        $sessionConfig = Config::get('session');
        if (strtolower($this->module) != 'admin') {
            preg_match("/(\.\w+\.\w+)/", $_SERVER['HTTP_HOST'], $matches);
            $sessionConfig['domain'] = $matches[0];
        }
        Config::set('session',$sessionConfig);

//        if (input('?get.terminal')) {
//            $_SESSION['terminal_name'] = input('get.terminal');
//        }

        Config::set('web_',(new Settings())->pluck('value','name'));

        View::share('module',       $this->module);
        View::share('controller',   $this->controller);
        View::share('currentController',   $this->controller);
        View::share('actionName',   $this->actionName);
        View::share('config',       Config::get('web_'));
        View::share('siteName',     Config::get('web_.site_name'));
        View::share('siteHome',     $this->siteHome());

        $this->mobile_site();
    }

    protected function mobile_site() {
        $this->theme('pc');
        //$actionName = strtolower($this->module.'/'.$this->controller.'/'.$this->actionName);
        //$url = $this->request->url(true);
        if (isMobile() && strtolower($this->module) != 'admin') {
            $this->theme('mobile');
        }

        // 手机访问www
        /*if (isMobile() && (substr($url, 0, 11) == 'http://www.')) {
            if (in_array($actionName, $this->mobile_actions)) {
                $this->redirect(preg_replace('#^http://www.#', 'http://m.', $url) ,301);
            }
        }*/
    }

    protected function theme($theme) {
        $view = app('view')->getFinder();
        // 重新定义视图目录
        $view->prependLocation(resource_path('views/'.strtolower($this->module).'/'.$theme));
        //Config::set('view.paths',);
    }

    // 站点主页 ，区分手机端
    protected function siteHome($mobile = null, $name = ['m', 'www']) {
        $host = $_SERVER["HTTP_HOST"];
        $scheme = $_SERVER["REQUEST_SCHEME"];
        if (($pos = strpos($host	, '.')) !== false) {
            $domain = substr($host, ++$pos);
            if (is_null($mobile)) {
                if (isMobile()) {
                    $mobile = true;
                }
            }
            if ($mobile)
                return $scheme.'://'.$name[0].'.'.$domain;
            else
                return $scheme.'://'.$name[1].'.'.$domain;
        }
    }
}
