<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/16
 * Time: 11:14
 */

namespace App\Http\Models;


class UserLoginlog extends Common
{
    public $timestamps = false;

    protected  $table = 'user_loginlog';
    /**
     * 不可以被赋值的属性。
     * @var array
     */
    protected $guarded = [
    ];
}