<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/2/2
 * Time: 17:12
 */

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;

class UploadController extends CommonController
{

    public function uploadify() {
        parent::_uploadify();
        return view('public.uploadify');
    }

    public function up() {
        return parent::_up(auid());
    }

    // 实现CKEDITOR上传的回调函数，有检查是否重复上传
    public function editorup(){
        parent::_editorup(auid());
    }
}