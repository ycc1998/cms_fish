<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/16
 * Time: 10:33
 */

namespace App\Http\Models;


class Adpos extends Common
{
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //设置表名
    protected $table = 'adpos';

    /**
     * 不可以被赋值的属性。
     * @var array
     */
    protected $guarded = [
    ];

    protected $fillable = [
        'name','type','htmlcode','intro','enabled'
    ];

    public function getAdsAttribute()
    {
        return (new Ad())->where('adpos_id',$this->id)->count();
    }
}
