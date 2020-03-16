<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/16
 * Time: 10:25
 */

namespace App\Http\Models;


class Ad extends Common
{
    protected $table = 'ad';

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    /**
     * 不可以被赋值的属性。
     * @var array
     */
    protected $guarded = [
    ];
}