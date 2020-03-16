<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/1/19
 * Time: 19:23
 */

namespace App\Http\Models;


use Illuminate\Support\Facades\Config;

class File extends Common
{
    protected $table = 'file';

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    /**
     * 不可以被赋值的属性。
     * @var array
     */
    protected $guarded = [
    ];

    public function getPathAttribute()
    {
        $config = Config::get('upload.'.$this->config);
        return '/uploads/'.$config['save_path'].'/'.$this->savename;
    }

    public function deleteFile($id) {
        $file = $this->where('id', $id)->first();
        $config = Config::get('upload.'.$file['config']);
        @unlink(DOCUMENT_ROOT.'/uploads/'.$config['save_path'].'/'.$file['savename']);
        return $this->where('id', $id)->delete();
    }

    // 获取真实路径
    public function getRealPath($id) {
        $file = $this->where('id', $id)->first();
        $config = Config::get('upload.'.$file['config']);
        return $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$config['save_path'].'/'.$file['savename'];
    }

}