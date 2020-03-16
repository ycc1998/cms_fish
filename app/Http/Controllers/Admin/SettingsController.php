<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/1/18
 * Time: 19:11
 */

namespace App\Http\Controllers\Admin;


use App\Http\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SettingsController extends CommonController
{
    public function form(Request $request) {
        // 显示设置数据
        if ($request->isMethod('get')){
            $settingsModel = new Settings();
            $settings = $settingsModel->pluck('value','name');
            View::share('settings', $settings);
        }else {
            // 处理保存和添加
            $data = array();
            $settingsModel = new Settings();
            //找到数据表中原来拥有的字段
            $fields = $settingsModel->pluck('name')->toArray();
            foreach ($_POST as $name => $value) {
                if (preg_match('/^config_/i', $name)) {
                    $data[substr($name, 7)] = $value;
                }
            }

            foreach ($data as $key => $value) {
                if (in_array($key,$fields)) {
                    $settingsModel->where('name', $key)->update(['value'=>$value]);
                } else {
                    $settingsModel->create(array('name'=>$key, 'value'=>$value));
                }
            }
            return $this->success('保存设置成功', route('admin.settings.form'),['direct'=>false]);
        }
        return view('settings.from');
    }

}