<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/16
 * Time: 10:29
 */

namespace App\Http\Models;



class AdminOplog extends Common
{
    protected $table = 'admin_oplog';



    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    /**
     * 不可以被赋值的属性。
     * @var array
     */
    protected $guarded = [
    ];
}