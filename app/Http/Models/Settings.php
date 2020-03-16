<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/16
 * Time: 15:22
 */

namespace App\Http\Models;


class Settings extends Common
{
    protected $table = 'settings';
    public $timestamps = false;

    protected $primaryKey = 'name';
}