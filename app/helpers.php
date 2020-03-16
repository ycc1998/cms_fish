<?php

// 格式化树型结构
// $arr = array(
//    array('id' => 1, 'pid' => 0, 'name' => 'root1'),
//    array('id' => 2, 'pid' => 0, 'name' => 'root2'),
//    array('id' => 3, 'pid' => 0, 'name' => 'root3'),
//    array('id' => 4, 'pid' => 1, 'name' => 'root1-child1'),
//    array('id' => 5, 'pid' => 4, 'name' => 'root1-child1-child1'),
//    array('id' => 6, 'pid' => 2, 'name' => 'roo2-child1'),
//);
// printr(get_tree($arr));
function get_tree($array, $pid=0) {
    $parents = array($pid); // stack
    $tree = array();
    while (($pid = array_pop($parents)) !== NULL) {
        foreach ($array as $key => $value) {
            if ($value['pid'] == $pid) {
                $value['level'] = count($parents); // 统计父级分类个数
                $tree[] = $value;
                $parents[] = $value['pid'];
                $parents[] = $value['id'];
                unset($array[$key]); // 清除已经遍历的元素，其它语言如果没有unset可以使用一个元素标记状态
                break; // 深度优先，只遍历一个分支
            }
        }
    }
    return $tree;
}
//判断是否是移动端访问

function isMobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return TRUE;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) {
        return stristr($_SERVER['HTTP_VIA'], "wap") ? TRUE : FALSE;// 找不到为flase,否则为TRUE
    }
    // 判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'mobile','nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips',
            'panasonic', 'alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian',
            'ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return TRUE;
        }
    }
    if (isset ($_SERVER['HTTP_ACCEPT'])) { // 协议法，因为有可能不准确，放到最后判断
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== FALSE) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === FALSE || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return TRUE;
        }
    }
    return FALSE;

}

/**
 * 格式化日期显示
 * @param integer $timestamp  要显示的UNIX时间纪元
 * @param String $format      要显示的日期时间格式
 * @param boolean $convert    是否要对日期格式进行自动转换
 */
function date_formate($timestamp, $format = "", $convert = false) {
    if (is_string($timestamp)) {
        return $timestamp;
    }

    $_formate = Config('date_formate');
    if (empty($format)) {
        $format = empty($_formate) ? 'Y/m/d H:i:s' : $_formate;
    }

    if (intval($timestamp) == 0) {
        return '-';
    }

    $s = date($format, (int)$timestamp);

    if ($convert == TRUE) {
        $now = time();
        $interval = $now - $timestamp;

        //分钟内
        if ($interval < 60) {
            return '<span title="' . $s . '">' . $interval . '秒前</span>';
        }
        //小时内
        if ($interval < 3600) {
            return '<span title="' . $s . '">' . intval($interval / 60) . '分钟前</span>';
        }
        //一天内
        if ($interval < 86400) {
            return '<span title="' . $s . '">' . intval($interval / 3600) . '小时前</span>';
        }
    }
    return $s;
}

function get_real_size($size) {
    $kb = 1024;         // Kilobyte
    $mb = 1024 * $kb;   // Megabyte
    $gb = 1024 * $mb;   // Gigabyte
    $tb = 1024 * $gb;   // Terabyte
    if($size < $kb) {
        return $size.'Byte';
    }else if($size < $mb) {
        return round($size / $kb,2).'KB';
    }else if($size < $gb) {
        return round($size / $mb,2).'MB';
    }else if($size < $tb) {
        return round($size / $gb,2).'GB';
    }else {
        return round($size / $tb,2).'TB';
    }
}

//目录的实际大小
function dirsize($dir) {
    $dh = @opendir($dir);
    $size = 0;
    while($file = @readdir($dh)) {
        if ($file != '.' && $file != '..') {
            $path = $dir.'/'.$file;
            if (@is_dir($path)) {
                $size += dirsize($path);
            } else {
                $size += @filesize($path);
            }
        }
    }
    @closedir($dh);
    return $size;
}

function memberDayCount($y = null, $m = null, $day = null) {
    if (is_null($y))
        $y   = date('Y');
    if (is_null($m))
        $m   = date('n');
    if (is_null($day))
        $day = date('j');

    $member = new \App\Http\Models\User();
    return $member->where([
        ['reg_day',$day],
        ['reg_month', $m],
        ['reg_year', $y]
    ])->count();
}

function FinanceDayCount($y = null, $m = null, $day = null) {
    if (is_null($y))
        $y   = date('Y');
    if (is_null($m))
        $m   = date('n');
    if (is_null($day))
        $day = date('j');

    $finance = new \App\Http\Models\UserFinance();
    return $finance->where([
        ['charge_day',$day],
        ['charge_month', $m],
        ['charge_year', $y]
    ])->count();
}

function in_array_case($value,$array){
    return in_array(strtolower($value),array_map('strtolower',$array));
}

function get_client_ip() {
    //获得IP地址
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    $onlineip = addslashes($onlineip);
    @preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
    $onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
    return $onlineip;
}

function getControllerNameByUrl($url) {
    $_pos = strpos($url,'/');
    if ($_pos !== false) {
        $_controllerName = substr($url, 0, $_pos);
    }

    return $_controllerName;
}

function getLeftMenuId($controllers,$controller, $kid) {
    if (in_array(strtolower($controller), $controllers[$kid])) {
        return true;
    }
    return false;
}

// 为了防止和前台冲突，必须使用TP实现的SESSION接口来获取值
function auid() {
    //$request = new \Illuminate\Http\Request();
    return !empty(session('uid')) ? session('uid') : null;
}
function loginAdmin() {
    ///$request = new \Illuminate\Http\Request();
    return !empty(session('username')) ? session('username') : '';
}

 function _order($field, $name,$route)
{
    $order = $field.'-desc';
    $class = 'sort';
    $orderValue = '';
    $_field = '';
    if(isset($_GET['order']))
        list($_field, $orderValue) = explode('-',  urldecode($_GET['order']));

    if ($field == $_field) {
        if ($orderValue == 'desc') {
            $class .= ' sort-down';
            $order = $_field.'-asc';
        }else if ($orderValue == 'asc') {
            $class .= ' sort-up';
            $order = $_field.'-desc';
        }
    }
    $url = route($route,array_merge($_GET, array('order' => $order)));
    //$url = url(request()->controller().'/'.request()->action(), array_merge($_GET, array('order' => $order)));
    return '<a href="'.$url.'" class="'.$class.'">'.$name.'</a>';
}

 function _filterMenusBySelect($filterMenus) {
    $_filterHTML = '<div class="btn-group"><button style="" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						      #replace#
							<span class="caret"></span>
						   </button>
						   <ul class="dropdown-menu dropdown-menu-right" role="menu">';

    $_i = 0;
    $_hasParam = 0;
    foreach($filterMenus as $key => $_filterMenus) {
        foreach($_filterMenus as $k=>$v) {
            if(isset($_GET[$key]) && !$_hasParam){
                $_hasParam = $k == 	$_GET[$key];
            }
        }
    }

    $_fg = true;
    foreach($filterMenus as $key => $_filterMenus) {
        if (!$_fg){
            $_filterHTML .= '<li class="divider"></li>';
        }
        $_fg = false;
        foreach($_filterMenus as $k=>$v) {

            $_has = false;
            if($_hasParam){
                if(isset($_GET[$key])){
                    $_has = $k == $_GET[$key];
                }
            }else{
                $_has = $_i == 0;
            }

            $_i++;
            $_class = $_has ? 'active' : '';
            if ($_has) {
                $_filterHTML = str_replace('#replace#', $v['name'],$_filterHTML);
            }

            $_filterHTML .= '<li class="'.$_class.'"><a href="'.route($v['url'], $v['args']).'">'.$v['name'].'</a></li>';
        }
    }
    return $_filterHTML.'</ul></div>';
}

 function _filterMenus($filterMenus) {

    $_filterHTML = '';
    $_i = 0;
    $_hasParam = 0;
    foreach($filterMenus as $key => $_filterMenus) {
        foreach($_filterMenus as $k=>$v) {
            if(isset($_GET[$key]) && !$_hasParam){
                $_hasParam = $k == 	$_GET[$key];
            }
        }
    }
    foreach($filterMenus as $key => $_filterMenus) {
        $_filterHTML .= '<div class="btn-group">';
        foreach($_filterMenus as $k=>$v) {
            $_has = false;
            if($_hasParam){
                if(isset($_GET[$key])){
                    $_has = $k == $_GET[$key];
                }
            }else{
                $_has = $_i == 0;
            }
            $_i++;
            $_class = $_has ? 'btn btn-default btn-tab active' : 'btn btn-default btn-tab';

            $badge = '';
            if (isset($v['function'])) {
                $badge = $v['function']();
            }
            $_filterHTML .= '<a class="'.$_class.'" href="'.route($v['url'], $v['args']).'">'.$v['name'].$badge.'</a>';
        }
        $_filterHTML .= '</div>';
    }
    return $_filterHTML;
}

//截取字数
function trimmed_title($text, $limit=12, $omit = true) {
    if ($limit) {
        $val = csubstr($text, 0, $limit);
        return $val[1] ? $val[0] . ($omit==true ? "..." : "") : $val[0];
    } else {
        return $text;
    }
}

function csubstr($text, $start=0, $limit=12) {
    if (function_exists('mb_substr')) {
        $more = (mb_strlen($text, 'UTF-8') > $limit) ? TRUE : FALSE;
        $text = mb_substr($text, 0, $limit, 'UTF-8');
        return array($text, $more);
    } elseif (function_exists('iconv_substr')) {
        $more = (iconv_strlen($text) > $limit) ? TRUE : FALSE;
        $text = iconv_substr($text, 0, $limit, 'UTF-8');
        return array($text, $more);
    } else {
        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);
        if(func_num_args() >= 3) {
            if (count($ar[0])>$limit) {
                $more = TRUE;
                $text = join("",array_slice($ar[0],0,$limit))."...";
            } else {
                $more = FALSE;
                $text = join("",array_slice($ar[0],0,$limit));
            }
        } else {
            $more = FALSE;
            $text =  join("",array_slice($ar[0],0));
        }
        if ($returnArray) {

        }
        return array($text, $more);
    }
}


// 相册名称
function albumName($name = 'images', $getAlbums = false) {
    $albums = [];
    $upload = Config('upload');
    foreach($upload as $key=>$value) {
        $albums[$key] = $value['name'];
    }
    if ($getAlbums) {
        return $albums;
    }
    return isset($albums[$name]) ? $albums[$name] : '';
}

function accountName($uid, $user = null) {
    if (is_null($user)) {
        $user = \App\Http\Models\User::find($uid);
    }
    if (!$user) {
        return false;
    }

    if ($user['type'] == 'admin' || $user['reg_account_type'] == '0') {
        return $user['username'];
    }

    if ($user['reg_account_type'] == '1') {
        return $user['email'];
    }else if($user['reg_account_type'] == '2') {
        return $user['mobile'];
    }
}

function download($filename, $showname='') {
    $file = FileDownload::createFromFilePath($filename);
    $file->sendDownload($showname);
}

// 递归的array_change_key_case
function array_case_upper_recursion($array, $case = CASE_UPPER) {
    if (is_array($array))
        $array = array_change_key_case($array, $case);

    foreach($array as $k=>$v) {
        if (is_array($v)) {
            $array[$k] = array_case_upper_recursion($v, $case);
        }
    }
    return $array;
}
// 前台上传必须手动指定 $limit_num 参数
function uploadFilesCheck($name, $limit_num = 0) {
    if (isset($_POST[$name])) {
        $real_num = empty($_POST[$name]) ? 0 : count(explode(',', $_POST[$name]));
        $post_limit = 0;
        if ($limit_num > 0) {
            $post_limit = intval($limit_num);
        }else if(isset($_POST['multi_num'])) {
            $post_limit = intval(decrypt($_POST['multi_num']));
        }

        if ( $post_limit >= $real_num) {
            return true;
        }
    }
    return false;
}

/**
 * 转化 \ 为 /
 *
 * @param	string	$path	路径
 * @return	string	路径
 */
function dir_path($path, $dir = true) {
    $path = str_replace('\\', '/', $path);
    if ($dir) {
        if(substr($path, -1) != '/') $path = $path.'/';
    }
    return $path;
}


function path_escaped($path) {
    return dir_path($path, false);
}

function _imageUpload($configName, $name, $fids, $setMain = true, $is_multi = null, $multi_num = null) {

    $config = Illuminate\Support\Facades\Config::get('upload.' . $configName);

    if (is_null($is_multi)) {
        $is_multi = $config['is_multi'];
    }

    if (is_null($multi_num)) {
        $multi_num = $config['multi_num'];
    }

    $upload_id = 'fids_'.$configName.'_'.$name;
    // 上传组件HTML支持
    Illuminate\Support\Facades\View::share('upload_id'  , $upload_id);
    Illuminate\Support\Facades\View::share('upload_name', $name);
    $files = [];
    $files = uploadFiles($fids);

    Illuminate\Support\Facades\View::share('files',     $files);
    Illuminate\Support\Facades\View::share('fids',      $fids);
    Illuminate\Support\Facades\View::share('setMain',   $setMain);
    Illuminate\Support\Facades\View::share('multi_num', $multi_num);
    Illuminate\Support\Facades\View::share('is_multi',  $is_multi);
    Illuminate\Support\Facades\View::share('config',    $configName);
    return view('public.imageUpload');
}

function uploadFiles($fids) {
    $files = [];
    if (!empty($fids)) {
        if (!is_array($fids)){
            $fids = [$fids];
        }
        $files = (new \App\Http\Models\File())->whereIn('id',$fids)->get();

    }
    return $files;
}
function memberCount() {
    $member = new \App\Http\Models\User();
    return $member->where(['type'=>'member'])->count();
}

function isVIP($user) {
    if (is_int($user) && $user > 0) {
        $user = \App\Http\Models\User::find($user);
    }
    if ($user instanceof \App\Http\Models\User) {
        if ($user['vip_final'] == 1) {
            return '终身VIP会员';
        }

        if ($user['vip_time'] > time()) {
            return 'VIP会员有效期 ' . date_formate($user['vip_time'],'Y-m-d H:i');;
        }
    }
    return false;
}

/**
 * 格式化商品价格
 *
 * @access  public
 * @param   float   $price  商品价格
 * @return  string
 */
function price_format($price, $change_price = true)
{
    if($price==='') {
        $price=0;
    }

    switch (Illuminate\Support\Facades\Config::get('web_.price_format')) {
        case 0:
            $price = number_format($price, 2, '.', '');
            break;
        case 1: // 保留不为 0 的尾数
            $price = preg_replace('/(.*)(\\.)([0-9]*?)0+$/', '\1\2\3', number_format($price, 2, '.', ''));

            if (substr($price, -1) == '.')
            {
                $price = substr($price, 0, -1);
            }
            break;
        case 2: // 不四舍五入，保留1位
            $price = substr(number_format($price, 2, '.', ''), 0, -1);
            break;
        case 3: // 直接取整
            $price = intval($price);
            break;
        case 4: // 四舍五入，保留 1 位
            $price = number_format($price, 1, '.', '');
            break;
        case 5: // 先四舍五入，不保留小数
            $price = round($price);
            break;
    }
    return sprintf(Illuminate\Support\Facades\Config::get('web_.currency_format'), $price);
}

// 获取头像地址
function getAvatarPath($uid, $size = '150x150') {
    $size = strtolower($size);
    $user = \App\Http\Models\User::find($uid);

    if ($user['avatar']) {
        $model = new \App\Http\Models\File();
        $file = $model->where('id', $user['avatar'])->first();
        if (!empty($file)) {
            if (empty($size)) {
                return $file->path;
            }else {
                $dir  = dirname($file->path);
                $name = basename($file->path);
                return $dir.'/'.$size.'_'.$name;
            }
        }
    }
    return '/static/images/default_avatar.jpg';
}

// 记录财务
function userFinace($uid, $email, $mobile, $total, $intro) {
    \App\Http\Models\UserFinance::create([
        'uid'    => $uid,
        'email'  => $email,
        'mobile' => $mobile,
        'charge_year'  => date('Y', time()),
        'charge_month' => date('n', time()),
        'charge_day'   => date('j', time()),
        'charge'       => $total,
        'intro'        => $intro,
    ]);
}