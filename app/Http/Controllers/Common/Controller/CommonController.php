<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/16
 * Time: 13:49
 */

namespace App\Http\Controllers\Common\Controller;

use App\Http\Models\File;
use App\Http\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class CommonController extends Controller
{
    protected $list_rows      = 20;
    protected $request        = null;
    protected $module         = '';
    protected $controller     = '';
    protected $actionName     = '';

    protected $mobile_actions = [];

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

    public function __construct(Request $request) {

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

//        $sessionConfig = Config::get('session');
//        if (strtolower($this->module) != 'admin') {
//            preg_match("/(\.\w+\.\w+)/", $_SERVER['HTTP_HOST'], $matches);
//            $sessionConfig['domain'] = $matches[0];
//        }
//        Config::set('session',$sessionConfig);
//
////        if (input('?get.terminal')) {
////            $_SESSION['terminal_name'] = input('get.terminal');
////        }
//
//        Config::set('web_',(new Settings())->pluck('value','name'));
//
//        View::share('module',       $this->module);
//        View::share('controller',   $this->controller);
//        View::share('actionName',   $this->actionName);
//        View::share('config',       Config::get('web_'));
//        View::share('siteName',     Config::get('web_.site_name'));
//        View::share('siteHome',     $this->siteHome());
//
//        $this->mobile_site();
    }

    protected function theme($theme) {
        $view = app('view')->getFinder();
        // 重新定义视图目录
        $view->prependLocation(resource_path('views/'.strtolower($this->module).'/'.$theme));
        //Config::set('view.paths',);
    }

    protected function terminalName() {
        $name = isset($_SESSION['terminal_name']) ? strtoupper($_SESSION['terminal_name']) : '';
        if (!in_array_case($name, ['ANDROID', 'IOS'])) {
            if ($this->request->isMobile()) {
                return 'MOBILE';
            }else {
                return 'PC';
            }
        }
        return $name;
    }

    protected function _uploadify() {

        $_config   = request('upload_config','');
        $config    = decrypt($_config);
        $upload_id = decrypt(request('upload_id',''));

        // 上传组件HTML支持
        View::share('upload_id', $upload_id);
        View::share('config', $config);
        $upload = Config::get('upload.' . $config);

        $is_multi = isset($_GET['is_multi']) ? $_GET['is_multi'] : $upload['is_multi'];
        $multi_num = isset($_GET['multi_num']) ? $_GET['multi_num'] : $upload['multi_num'];
        $maxSize  = $upload['size'] != 0 ? get_real_size($upload['size']) : 0;
        $exts = "";
        foreach (explode(',', $upload['ext']) as $ext) {
            $exts .= "*." . trim($ext) . ";";
        }
        View::share('exts', rtrim($exts, ';'));
        View::share('sizeLimit', $maxSize);
        View::share('is_multi', $is_multi);
        View::share('config', $_config);
        View::share('multi_num', $multi_num > 99 ? 99 : $multi_num);
    }

    protected function _up($uid) {
        $request = $this->request;
        $verifyToken = md5('unique_salt' . $_POST['timestamp']);
        if (!empty($_FILES) && isset($_POST['token']) && $_POST['token'] == $verifyToken) {
            $configName = decrypt($_POST['upload_config']);
            $config = Config::get('upload.' . $configName);
            $savePath = '/uploads/'.trim($config['save_path'],'/').'/';
            $file = $request->file('Filedata');
            $size = $file->getClientSize();

            if (!$file->isValid()){
                return response(['status'=>false,'msg'=>'图片上传无效']);
            }

            if (!in_array($file->getClientOriginalExtension(),explode(',',$config['ext']))){
                return response(['status'=>false,'msg'=>'图片类型不符']);
            }
            if ($size > $config['size']){
                return response(['status'=>false,'msg'=>'图片过大']);
            }

            $dateTime = date('Ymd',time());
            $fileName=md5(time().rand(1,1000)).'.'.$file->getClientOriginalExtension();
            $info = $file->move(DOCUMENT_ROOT . $savePath.$dateTime,$fileName);
            if ($info) {
                $name = $fileName;
                $saveName        = $dateTime.'/'.$fileName;
                $result = File::create([
                    'name'=>$name,
                    'uid' => $uid,
                    'savename' => $saveName,
                    'mimes'=>$info->getMimeType(),
                    'ext' => $file->getClientOriginalExtension(),
                    'config' => $configName,
                    'md5' => '',
                    'size' => $size
                ]);
                // 不能用save()的返回值，返回的是插入的记录数
                return response([
                    'name'=>$name,
                    'id'=>$result->id,
                    'path'=>$savePath.$saveName,
                    'md5'=>'',
                    'status'=>true
                ]);
                // 用ajax的方式返回回调信息
            } else {
                // 上传失败获取错误信息
                return response(['status'=>false,'msg'=>'上传失败']);
            }

        }else {
            return response(['status'=>false,'msg'=>'上传失败']);
        }
    }

    // 实现CKEDITOR上传的回调函数，有检查是否重复上传
    protected function _editorup($uid){
        $request = $this->request;
        $config = Config::get('upload.editor');

        $file = $request->file('upload');
        $_callback = $_REQUEST["CKEditorFuncNum"];  //编辑器回调函数
        $size = $file->getClientSize();

        if (!$file->isValid()){
            $html = '<font color="red" size="2">' . '图片上传无效' . '</font>';
            exit($html);
        }

        if (!in_array($file->getClientOriginalExtension(),explode(',',$config['ext']))){
            $html = '<font color="red" size="2">' . '图片类型不符合' . '</font>';
            exit($html);
        }
        if ($size > $config['size']){
            $html = '<font color="red" size="2">' . '图片过大' . '</font>';
            exit($html);
        }

        $fileName=md5(time().rand(1,1000)).'.'.$file->getClientOriginalExtension();
        $dateTime = date('Ymd',time());
        $info = $file->move(DOCUMENT_ROOT.'/uploads/editor/'.$dateTime,$fileName);

        if ($info) {
            $name = $fileName;
            $_config         = 'editor';
            $saveName        = $dateTime.'/'.$fileName;

            File::create([
                'name'=>$name,
                'uid' => $uid,
                'savename' => $saveName,
                'mimes'=>$info->getMimeType(),
                'ext' => $file->getClientOriginalExtension(),
                'config' => $_config,
                'md5' => '',
                'size' => $size
            ]);

        }else {
            $html = '<font color="red" size="2">' . '文件上传失败！' . '</font>';
            exit($html);
        }


        $url = path_escaped('/uploads/editor/'.$saveName);
        $html = "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction('".$_callback."', '" . $url . "','');</script>";
        exit($html);
    }


    // 阿里大于短信发送
    protected function sendSMS($mobile, $mobile_code, $sms_code = '', $signName = '') {
        $settings = Config::get();
        if (empty ( $settings ['dayu_appkey'] ) || empty ( $settings ['dayu_secretkey'] )) {
            throw new \think\Exception('系统没有设置短信服务器');
        }

        if (empty($signName)) {
            $signName = $settings['dayu_signname'];
        }

        if (empty($sms_code)) {
            $sms_code = $settings['dayu_verifymobiletpl'];
        }

        // 导入第三方类库
        Vendor ( 'TaobaoSDK.TopSdk' );
        // 创建淘宝接口调用实例
        $c = new \TopClient();

        $c->format    = 'json';
        $c->appkey    = $settings ['dayu_appkey'];
        $c->secretKey = $settings ['dayu_secretkey'];
        $req = new \AlibabaAliqinFcSmsNumSendRequest ();
        $req->setSmsType ("normal");
        $req->setSmsFreeSignName ($signName);
        $req->setSmsParam ("{\"mobile_code\":\"{$mobile_code}\"}");
        $req->setRecNum ($mobile);
        $req->setSmsTemplateCode($sms_code);
        $resp = $c->execute($req);
        //print_r($resp);
    }


    protected function  sendEmail($content, $toAddress, $subject = "") {
        $this->_sendEmail($toAddress, $subject, $content);
    }

    //发送电子邮件函数
    protected function _sendEmail($to, $subject = 'not subject', $body)  {
        $settings  =   Config::get();
        $loc_host  =   $settings['email_smtp'];                // 发信主机名
        $smtp_acc  =   $settings['email_serverusername'];      // SMTP认证的用户名
        $smtp_pass =   $settings['email_serverpassword'];      // SMTP认证的密码
        $smtp_host =   $settings['email_smtp'];                // SMTP认证的服务器
        $from      =   $settings['email_serverusername'];      // 发信人的邮件地址
        $port      =   $settings['email_port'];

        $subject = "=?UTF-8?B?".base64_encode($subject)."?=";  //没这行可就的乱码喽
        $headers = "Content-Type: text/html; charset=\"UTF-8\"\r\nContent-Transfer-Encoding: base64";
        $lb="\r\n";
        $hdr = explode($lb,$headers);
        if($body) {$bdy = preg_replace("/^\./","..",explode($lb,$body));}
        $smtp = array(
            array("EHLO ".$loc_host.$lb,"220,250","HELO error: "),
            array("AUTH LOGIN".$lb,"334","AUTH error:"),
            array(base64_encode($smtp_acc).$lb,"334","AUTHENTIFICATION error : "),
            array(base64_encode($smtp_pass).$lb,"235","AUTHENTIFICATION error : "));
        $smtp[] = array("MAIL FROM: <".$from.">".$lb,"250","MAIL FROM error: ");
        $smtp[] = array("RCPT TO: <".$to.">".$lb,"250","RCPT TO error: ");
        $smtp[] = array("DATA".$lb,"354","DATA error: ");
        $smtp[] = array("From: ".$from.$lb,"","");
        $smtp[] = array("To: ".$to.$lb,"","");
        $smtp[] = array("Subject: ".$subject.$lb,"","");
        foreach($hdr as $h) {$smtp[] = array($h.$lb,"","");}
        $smtp[] = array($lb,"","");
        if($bdy) {foreach($bdy as $b) {$smtp[] = array(base64_encode($b.$lb).$lb,"","");}}
        $smtp[] = array(".".$lb,"250","DATA(end)error: ");
        $smtp[] = array("QUIT".$lb,"221","QUIT error: ");

        $fp = @fsockopen($smtp_host, $port);
        if (!$fp) echo "<b>Error:</b> Cannot conect to ".$smtp_host."<br>";
        while($result = @fgets($fp, 1024)){
            if(substr($result,3,1) == " ") { break; }
        }

        $result_str="";
        foreach($smtp as $req){
            @fputs($fp, $req[0]);
            if($req[1]){
                while($result = @fgets($fp, 1024)){
                    if(substr($result,3,1) == " ") { break; }
                };
                if (!strstr($req[1],substr($result,0,3))){
                    $result_str.=$req[2].$result."<br>";
                }
            }
        }
        @fclose($fp);
        return $result_str;
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

    // 获得地址信息
    public function area() {
        $pid = input('get.pid');
        $area = new Area();
        $areas = $area->where('pid', $pid)->select();
        return json($areas);
    }



}