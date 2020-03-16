<?php
return [

    // 默认图片上传配置，文章缩略图
    'images' => [
        'name'         => '默认相册',
        'size'         => 5*1024*1024,              // 上传的文件大小限制
        'ext'          => 'jpg,png,gif,jpeg',       // 允许上传的文件后缀
        'save_path'    => 'images',
        'rule'         => 'date',
        'is_multi'     => true,                     // 是否允许上传多文件
        'multi_num'    => 10,
        'servers'      => [

        ],

        'allow_thumbs_size'    => ['695x250'],
    ],

    'ad' => [
        'name'         => '广告相册',
        'size'         => 5*1024*1024,
        'ext'          => 'jpg,png,gif,jpeg',
        'save_path'    => 'ad',
        'rule'         => 'date',
        'is_multi'     => false,
        'multi_num'    => 1,
    ],

    'editor' => [
        'name'         => '编辑器相册',
        'size'         => 10*1024*1024,
        'ext'          => 'jpg,png,gif,jpeg',
        'save_path'    => 'editor',
        'rule'         => 'md5',
    ],

    'avatar' => [
        'name'         => '头像相册',
        'size'         => 1*1024*1024,
        'ext'          => 'jpg,png,gif,jpeg',
        'save_path'    => 'avatar',
        'rule'         => 'date',
    ],
];
