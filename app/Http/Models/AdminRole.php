<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/16
 * Time: 10:32
 */

namespace App\Http\Models;



class AdminRole extends Common
{
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //设置表名
    protected $table = 'admin_role';
    /**
     * 不可以被赋值的属性。
     * @var array
     */
    protected $guarded = [
    ];
}