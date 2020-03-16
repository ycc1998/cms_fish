<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/16
 * Time: 11:13
 */

namespace App\Http\Models;


class UserFinance extends Common
{
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //设置表名
    protected $table = 'user_finance';
    /**
     * 不可以被赋值的属性。
     * @var array
     */
    protected $guarded = [
    ];
}