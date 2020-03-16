<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/1/18
 * Time: 22:38
 */

namespace App\Http\Models;


class Links extends Common
{
    protected $table = 'links';

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected $fillable = [
        'name','url','enabled','class_key','position'
    ];

}