<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/15
 * Time: 17:15
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class IndexController extends CommonController
{
    public function index()
    {
        if (isset($_GET['phpinfo'])) {
            phpinfo();
            return;
        }

        if (function_exists('apache_get_modules'))
            $rewrite_module = apache_get_modules();
        else
            $rewrite_module = array();

        $sys_info = array(
            'os'               => PHP_OS,
            'rewrite_module'   => in_array('mod_rewrite', $rewrite_module) ? 'YES' : 'NO',
            'socket'           => function_exists('fsockopen') ? 'YES' : 'NO',
            'gd'               => extension_loaded("gd") ? 'YES' : 'NO',
            'max_filesize'     => ini_get('upload_max_filesize'),
            'webserver'        => $_SERVER['SERVER_SOFTWARE'],
            'timezone'         => Config::get('timezone'),
            'date'             => date_formate(time(),$format = "Y/m/d H:i:s", $convert = false),
            'dirsize'          => '<a onclick="return confirm(\'可能会占用过多服务器资源，请慎重操作！\')" href="'.route('admin.index',['getdirsize'=>1]).'">查看大小</a>',
        );

        if (function_exists('memory_get_usage'))
            $sys_info['memory_info'] = get_real_size(memory_get_usage());
        else
            $sys_info['memory_info'] = '';

        if(!empty($_GET['getdirsize']) && $_GET['getdirsize'] == '1')
            $sys_info['dirsize'] =  get_real_size(dirsize(app_path()));

        $sys_info['mysql_version'] = $this->_mysql_version();
        View::share('sys_info', $sys_info);
        View::share('statistics', Db::table('statistics')->where('id', 1)->first());

        return view('index.index');
    }

    private function _mysql_version()
    {
        $version = DB::select("select version() as ver");
        return $version[0]->ver;
    }
}