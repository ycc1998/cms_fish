<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/16
 * Time: 10:27
 */

namespace App\Http\Models;



class AdminLoginlog extends Common
{
    protected $table = 'admin_loginlog';

    public $timestamps = false;

    /**
     * 不可以被赋值的属性。
     * @var array
     */
    protected $guarded = [
    ];
}